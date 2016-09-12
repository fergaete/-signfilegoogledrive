<?php
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\GoogleAccount;

class GoogleAccountRepository extends BaseRepository {
	
	/**
	 * @var string
	 */
	protected $entityName = GoogleAccount::class;
}