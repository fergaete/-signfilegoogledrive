<?php
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Usuario;

class UsuarioRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = Usuario::class;
}