<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\Google\Authorization;
use AppBundle\Service\Google\UserService;
use AppBundle\Service\Google\DriveService;
use AppBundle\Service\Google\Credential\DoctrineOrmImplementation;
use AppBundle\Service\Google\Drive\PropertyService;

use AppBundle\Entity\Usuario;
use AppBundle\Entity\GoogleAccount;
use AppBundle\Entity\Transaccion;
use AppBundle\Entity\DetalleTransaccion;
use AppBundle\Entity\Archivo;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

use AppBundle\Service\Esign\Esigner\Client\SoapClient;
use AppBundle\Service\Esign\Esigner\Esigner;
use AppBundle\Service\Esign\Esigner\Entity\IntercambiaDoc;
use AppBundle\Service\Esign\Esigner\Entity\Encabezado;
use AppBundle\Service\Esign\Esigner\Entity\Parametro;
use AppBundle\Service\Esign\Esigner\EsignerException;

class DefaultController extends Controller {
    
    /**
     * @Route("/sign-in", name="sign_in")
     */
    public function signInAction(Request $request) { 
        $authorization = $this->get('app.service.google.authorization');
        return $this->render(
            'AppBundle:Default:signIn.html.twig', 
            array('authorization_url' => $authorization->getAuthorizationUrl('', ''))
        );
    }

    /**
     * @Route("/success", name="success")
     */
    public function successAction(Request $request) {
        return $this->render(
            'AppBundle:Default:success.html.twig'
        );
    }

    /**
     * @Route("/error", name="error")
     */
    public function errorAction(Request $request) {
        $token = $request->getSession()->get('_security.last_error')->getToken();
        $url = 'admin_login';
        if(!$token) {
            $message = 'No fue posible llevar a cabo la autenticación';
        } 
        else {    
            $message = 'Error al autenticar usuario, favor contactar a la mesa de ayuda';
            if($token instanceof OAuthToken) {                
                $credential = json_encode($token->getRawToken());
                $client = $this->get('app.factory.google.client_factory');
                $client->setAccessToken($credential);
                $client->revokeToken();
                $url = 'sign_in';
            }
        }
        $request->getSession()
                ->getFlashBag()
                ->add('error', $message);

        return $this->redirect($this->generateUrl($url));
    }

    /**
     * @Route("/open", name="open")
     */
    public function openAction(Request $request) {
        $client            = $this->get('app.factory.google.client_factory');
        $credentialService = $this->get('app.service.google.credential');
        $authorization     = $this->get('app.service.google.authorization');
        $state             = json_decode(stripslashes($request->get('state')));
        
		if(isset($state->ids)) {
			$fileIds = $state->ids;
		}
		if(isset($state->exportIds)) {
			$fileIds = $state->exportIds;
		}
        
        $credentials = $credentialService->getStoredCredentials($state->userId);
        $client->setAccessToken($credentials);
		
		$userService  = $this->get('app.service.google.user_service'); 	
		$driveService = $this->get('app.service.google.drive_service');
        
        $userInfo = $userService->getUserInfo($credentials);
		$file = $driveService->findFileById($fileIds[0]);
		
        $usuario = $this->get('security.context')->getToken()->getUser()->getUsuario();
        
        $archivo = $this->get('app.repository.archivo')->findOneBy(array('googleFileId' => $file->getId()));

        if(!$archivo) {
            $archivo = new Archivo(
                $file->getId(),
                $file->getOriginalFilename(),
                $file->getMimeType(),
                $driveService->isFilePublic($file)
            );
        }

        $archivo->setNombre($file->getOriginalFilename());
        $archivo->setMimeType($file->getMimeType());
        $archivo->setEsPublico($driveService->isFilePublic($file));
        
        $transaccion = new Transaccion();
        $this->saveDetalleTransaccion(
            DetalleTransaccion::ESTADO_INICIO, 
            'Apertura de archivo', 
            $transaccion,
            $archivo
        );

        $driveService->changeFilePermissionToPublic(new \Google_Service_Drive_Permission(), $file);

		return $this->render(
			'AppBundle:Default:open.html.twig',
			array(
				'user'     => $userInfo,
                'file'     => $file,
                'firmante' => $this->get('app.service.google.drive.property_service')->get(
                    $file->getId(),
                    PropertyService::KEY_USUARIO_FIRMA
                ),
                'transaccionId' => $transaccion->getId()
			)
		);
    }

    /**
     * @Route("/firmar", name="firmar")
     */
    public function firmarAction(Request $request) {
        try {
        
            $usuario = $this->get('security.context')->getToken()->getUser()->getUsuario();
            
            if(!$usuario->getCliente()) {
                $message = 'usuario no pertenece a ningún cliente';
                $this->get('monolog.logger.usuario')->error($message);
                return new JsonResponse(
                    $message,
                    403
                );
            }

            $googleAccount = $usuario->getGoogleAccount();
            $credential = $googleAccount->getCredential();
            $client  = $this->get('app.factory.google.client_factory');
            $client->setAccessToken($credential);

            $driveService = $this->get('app.service.google.drive_service');
            $file = $driveService->findFileById($request->get('fileId'));
            $fileContent = $driveService->download($file);
            
            $configuracion = $this->get('app.repository.configuracion')->findOneByUsuarioAndMimeType(
                $usuario,
                $file->getMimeType()
            );

            if(!$configuracion) {
                $message = sprintf('configuración no encontrada para mime type: %s ',$file->getMimeType());
                $this->get('monolog.logger.usuario')->error($message);
                return new JsonResponse(
                    $message, 
                    403
                );
            }

            $esigner = new Esigner(new SoapClient($usuario->getCliente()->getWsdl()));
            $intercambiaDocResult = $esigner->signDocument(
                new IntercambiaDoc (
                    new Encabezado(
                        $usuario->getUsername(), 
                        $request->get('password'), 
                        $configuracion->getNombre()
                    ),
                    new Parametro(base64_encode($fileContent), $file->title)
                )
            );

            $folder = $driveService->createFolderIfDoesNotExists(
                new \Google_Service_Drive_DriveFile(),
                $this->getParameter('google_drive_signed_folder')
            );

            $userInfo = $this->get('app.service.google.user_service')->getUserInfo($credential);

            $fileUpload = new \Google_Service_Drive_DriveFile();
            $parent = new \Google_Service_Drive_ParentReference();
            $parent->setId($folder->getId());
            $fileUpload->setParents(array($parent));
            $fileUpload->setTitle($intercambiaDocResult->getNombreDocumento());
            $fileUpload->setDescription($this->generateDescription($userInfo));

            $uploadedFile = $driveService->upload(
                $fileUpload, 
                array(
                  'data'       => base64_decode($intercambiaDocResult->getDocumento()),
                  'mimeType'   => 'application/octet-stream',
                  'uploadType' => 'media'
            ));

            $this->insertProperties($uploadedFile, $userInfo);

        }
        catch(\Exception $ex) {
            $this->get('monolog.logger.usuario')->error($ex->getMessage());
            return new JsonResponse($ex->getMessage(), 403);
        }

        $this->get('monolog.logger.usuario')->info(sprintf('documento [%s] firmado con éxito', $file->getTitle()));
        return new JsonResponse(array(
			"folder"  => "https://drive.google.com/drive/u/1/folders/" . $folder->getId(),
			"message" => "OK"
		));
    }

    /**
     * @Route("/normalizar-permisos", name="normalizar-permisos")
     */
    public function normalizarPermisosAction(Request $request) {
        try {
            $message = '';
            $usuario = $usuario = $this->get('security.context')->getToken()->getUser()->getUsuario();
            $fileId  = $request->get('fileId');

            $transaccion = $this->get('app.repository.transaccion')->findOneByIdAndUsuario(
                $request->get('transaccionId'),
                $usuario
            );

            if(!$transaccion) {
                return new JsonResponse(array(
                    "no existe transaccion para este usuario"
                ), 404);
            }

            $archivo = $this->get('app.repository.archivo')->findOneBy(array('googleFileId' => $fileId));
            
            if($archivo && !$archivo->esPublico()) {
                $this->saveDetalleTransaccion(
                    DetalleTransaccion::ESTADO_NORMALIZAR_PERMISOS, 
                    'Normalizando permisos de archivo',
                    $transaccion,
                    $archivo
                );

                try {
                    $client = $this->get('app.factory.google.client_factory');
                    $client->setAccessToken($usuario->getGoogleAccount()->getCredential());
                    $driveFactory = $this->get('app.factory.google.drive_factory');
                    $driveFactory->permissions->delete($fileId, 'anyone');
                    $message = "Permisos reestablecidos"; 

                     $this->saveDetalleTransaccion(
                        DetalleTransaccion::ESTADO_PERMISOS_NORMALIZADOS, 
                        $message,
                        $transaccion,
                        $archivo
                    );
                }
                catch(\Exception $ex) {
                    $this->saveDetalleTransaccion(
                        DetalleTransaccion::ESTADO_NORMALIZAR_PERMISOS_ERROR, 
                        $ex->getMessage(),
                        $transaccion,
                        $archivo
                    );
                }
            }

            return new JsonResponse(array(
                "message" => $message
            ));
        }
        catch(\Exception $ex) {
            return new JsonResponse(array(
                "message" => $ex->getMessage()
            ),  500);
        }
    }

    /**
     * @param UserInfo $userInfo
     * @return string
     */
    private function generateDescription($userInfo) {
        $formatter = new \IntlDateFormatter('es_CL', \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);
        $formatter->setPattern('dd-LLLL-yyyy HH:mm:ss z');
        
        return sprintf(
            'DOCUMENTO FIRMADO CON FIRMA ELECTRÓNICA AVANZADA %s Por %s',
            $formatter->format(new \DateTime()),
            $this->generateNombreUsuario($userInfo) . " (" . $userInfo->getEmail() . ")"
        );
    }

    /**
     * @param UserInfo $userInfo
     * @return string
     */
    private function generateNombreUsuario($userInfo) {
        return $userInfo->getGivenName() . " " .$userInfo->getFamilyName();
    }

    /**
     * @param \Google_Service_Drive_DriveFile $upload
     * @param UserInfo $userInfo
     */
    private function insertProperties(\Google_Service_Drive_DriveFile $uploadedFile, $userInfo) {
        try {
            $propertyService = $this->get('app.service.google.drive.property_service');
            $property = new \Google_Service_Drive_Property();
            $property->setVisibility('PUBLIC');
            
            $property->setKey(PropertyService::KEY_FECHA_FIRMA);
            $property->setValue(date('d-m-Y H:i:s T'));
            $propertyService->insert($property, $uploadedFile->getId());
            
            $property->setKey(PropertyService::KEY_USUARIO_FIRMA);
            $property->setValue($this->generateNombreUsuario($userInfo) . " <" . $userInfo->getEmail() . ">");
            $propertyService->insert($property, $uploadedFile->getId());
        }
        catch(\Exception $ex) {
            $logger = $this->get('logger');
            $logger->error(sprintf('error al agregar propiedades al documento %s', $uploadedFile->getId()));
        }
    }

    private function saveDetalleTransaccion($estado, $mensaje, Transaccion $transaccion, Archivo $archivo) {
        $usuario = $usuario = $this->get('security.context')->getToken()->getUser()->getUsuario();
        $transaccion->setUsuario($usuario);
        $detalleTransaccion = new DetalleTransaccion(
            $estado, 
            $mensaje, 
            $archivo
        );

        $detalleTransaccion->setTransaccion($transaccion);
        $detalleTransaccion->setArchivo($archivo);
        
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($transaccion);
        $em->persist($archivo);
        $em->persist($detalleTransaccion);
        $em->flush();
    }
}
