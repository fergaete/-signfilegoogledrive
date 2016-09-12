<?php 
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Cliente;

class ClienteRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = Cliente::class;
}