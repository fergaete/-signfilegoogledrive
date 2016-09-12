<?php
namespace AppBundle\Service\Google\Drive;
use AppBundle\Service\Google\Exception\DriveException;

class DriveService {
	
	/**
	 * @var \Google_Service_Drive
	 */
	private $service;
	
	/**
	 * @param \Google_Service_Drive $service
	 */
	public function __construct(\Google_Service_Drive $service) {
		$this->service = $service;
	}
	
	/**
     * @param int $file
	 * @return \Google_Service_Drive_DriveFile
     */
    public function findFileById($id) {
        return $this->service->files->get($id);
    }   

    /**
     * @param \Google_Service_Drive_DriveFile $file Drive File instance.
     * @return string The file's content if successful, null otherwise.
     */
    public function download(\Google_Service_Drive_DriveFile $file) {
        $downloadUrl = $file->getDownloadUrl();
        if($downloadUrl) {
            $request = new \Google_Http_Request($downloadUrl, 'GET', null, null);
            $httpRequest = $this->service->getClient()->getAuth()->authenticatedRequest($request);
            if($httpRequest->getResponseHttpCode() == 200) {
                return $httpRequest->getResponseBody();
            } 
            else {
                return null;
            }
        } 
        else {
            // The file doesn't have any content stored on Drive.
            return null;
        }
    }

    /**
     * @param \Google_Service_Drive_DriveFile $file Drive File instance.
     * @param array $options
     * @return \Google_Service_Drive_ChildReference
     */
    public function upload(\Google_Service_Drive_DriveFile $file, $options = array()) {
        return $this->service->files->insert($file, $options);
    }

    /**
     * @param string $folderName
     * @return \Google_Service_Drive_DriveFile | null
     * @throws DriveException  
     */
    public function findFolderByName($folderName) {
        try {
            
            $search = sprintf(
                "title='%s' AND '%s' in parents AND mimeType = 'application/vnd.google-apps.folder' AND trashed != true", 
                $folderName, 
                $this->service->about->get()->getRootFolderId()
            );

            $files = $this->service->files->listFiles(array("q" => $search));
        }
        catch(\Exception $ex) {
            throw new DriveException($ex);
        }

        if(!empty($files['items'])) {
            return $files['items'][0];
        }

        return null;
    }

    /**
     * @param \Google_Service_Drive_DriveFile $file
     * @param string $folderName
     * @return \Google_Service_Drive_DriveFile
     * @throws DriveException
     */
    public function createFolder(\Google_Service_Drive_DriveFile $file, $folderName) {
        try {
            $file->setTitle($folderName);
            $file->setDescription('Carpeta con Documentos Firmados');
            $file->setMimeType('application/vnd.google-apps.folder');

            return $this->service->files->insert(
                $file, 
                array(
                    'mimeType' => 'application/vnd.google-apps.folder',
                )
            );
        } 
        catch(\Exception $ex){
            throw new DriveException($ex);
        }        
    }

    /**
     * @param \Google_Service_Drive_DriveFile $file
     * @param string $folderName
     * @return \Google_Service_Drive_DriveFile
     * @throws DriveException
     */
    public function createFolderIfDoesNotExists(\Google_Service_Drive_DriveFile $file, $folderName) {
        $folder = $this->findFolderByName($folderName);
        
        if(!$folder) {
            $folder = $this->createFolder($file, $folderName);
        }

        return $folder;
    }

    /**
     * @var \Google_Service_Drive_Permission $permission
     * @var \Google_Service_Drive_DriveFile $file
     * @throws DriveException
     */
    public function changeFilePermissionToPublic(\Google_Service_Drive_Permission $permission, \Google_Service_Drive_DriveFile $file) {
        if(!$this->isFilePublic($file)) {
            try {
                $permission->setType('anyone');
                $permission->setRole('reader');
                $this->service->permissions->insert($file->getId(), $permission);
            }
            catch(\Exception $ex) {
                throw new DriveException($ex);
            }
        }
    }

    /**
     * @var \Google_Service_Drive_DriveFile $file
     * @return bool
     * @throws DriveException
     */
    public function isFilePublic(\Google_Service_Drive_DriveFile $file) {
        try {
            $this->service->permissions->get($file->getId(), 'anyone');    
        }
        catch(\Google_Service_Exception $ex) {
            $errors = $ex->getErrors();
            if($errors[0]['reason'] == 'notFound') { 
                return false;
            }
            else {
                throw new DriveException($ex);
            }
        }
        catch(\Exception $ex) {
             throw new DriveException($ex);
        }

        return true;
    }
}