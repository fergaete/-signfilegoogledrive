<?php
namespace AppBundle\Repository\Doctrine;
use Doctrine\ORM\EntityManager;

abstract class BaseRepository {
	
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @var string
	 */
	protected $entityName;

	/**
	 * @var EntityManager $em
	 */
	public function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * @var array $criteria
	 * @return Object|null 
	 */
	public function findOneBy(array $criteria) {
		return $this->entityManager->getRepository($this->entityName)->findOneBy($criteria);
	}
}