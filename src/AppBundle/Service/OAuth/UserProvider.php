<?php
namespace AppBundle\Service\OAuth;

use AppBundle\Repository\Doctrine\UsuarioRepository;
use AppBundle\Entity\GoogleAccount;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Doctrine\ORM\EntityManager;

class UserProvider extends OAuthUserProvider {
	
	/**
	 * @var UsuarioRepository
	 */
	private $repository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param UsuarioRepository $repository
     * @param EntityManager $entityManager
     */
	public function __construct(UsuarioRepository $repository, EntityManager $entityManager) {
		$this->repository = $repository;
        $this->entityManager = $entityManager;
	}

    /**
     * @param string $email
     * @return Usuario
     * @throws UsernameNotFoundException
     */
    private function findUsuarioByEmail($email) {
        $usuario = $this->repository->findOneBy(array(
            'email' => $email
        ));

        if(!$usuario) {
            throw new UsernameNotFoundException(sprintf('usuario %s no ha sido ingresado en la plataforma', $email));
        }

        return $usuario;
    }
    
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username) {
        return new OAuthUser($username, $this->findUsuarioByEmail($username));
    }

	/**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
    	$email   = $response->getEmail();
    	$usuario = $this->findUsuarioByEmail($email);
        
        if(!$usuario->getGoogleAccount()) {
            $googleAccount = new GoogleAccount();
            $googleAccount->setUserId($response->getUsername());
            $usuario->setGoogleAccount($googleAccount);
        }

        $usuario->getGoogleAccount()->setCredential(json_encode($response->getOAuthToken()->getRawToken()));
        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

    	return new OAuthUser($email, $usuario);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class) {
        return $class === 'AppBundle\\Service\\OAuth\\OAuthUser';
    }
}