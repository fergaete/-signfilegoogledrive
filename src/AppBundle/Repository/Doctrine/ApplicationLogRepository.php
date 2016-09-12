<?php
namespace AppBundle\Repository\Doctrine;
use AppBundle\Entity\ApplicationLog;

class ApplicationLogRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = ApplicationLog::class;
}