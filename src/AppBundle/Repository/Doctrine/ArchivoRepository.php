<?php 
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Archivo;

class ArchivoRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = Archivo::class;
}