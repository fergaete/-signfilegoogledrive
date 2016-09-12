<?php
namespace AppBundle\Service\Google\Credential;
use AppBundle\Entity\GoogleAccount;
use Doctrine\ORM\EntityManager;

class DoctrineOrmImplementation implements CredentialInterface {
	
	/**
	 * @var EntityManager
	 */
	private $orm;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	/**
	 * @var string $userId
	 * @return string, json string OAUTH 2
	 * @throws \RuntimeException 
	 */
	public function getStoredCredentials($userId) {
		$googleAccount = $this->findByUserId($userId);

		if(!$googleAccount) {
			throw new \RuntimeException('user credentials not found');
		}

		return $googleAccount->getCredential();
	}

	/**
	 * @var string $userId
	 * @var string $credentials, json string OAUTH 2
	 * @throws \RuntimeException 
	 */
	public function storeCredentials($userId, $credentials) {
		$googleAccount = $this->findByUserId($userId);

		if(!$googleAccount) {
			$googleAccount = new GoogleAccount();
			$googleAccount->setUserId($userId);
		}

		try {
			$googleAccount->setCredential($credentials);
			$this->em->persist($googleAccount);
			$this->em->flush();
		}
		catch(\Exception $ex) {
			throw new \RuntimeException($ex);
		}
	}

	/**
	 * @var string $userId
	 * @return GoogleAccount | null
	 */
	private function findByUserId($userId) {
		return $this->em->getRepository('AppBundle\Entity\GoogleAccount')->findOneBy(
			array(
				'userId' => $userId
		));
	}
}