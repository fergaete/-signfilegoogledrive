<?php
namespace AppBundle\Service\OAuth;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser as HWIOAuthUser;
use AppBundle\Entity\Usuario;

class OAuthUser extends HWIOAuthUser {

	private $usuario;

	/**
     * @param string $username
     * @param Usuario $usuario
     */
	public function __construct($username, Usuario $usuario) {
		parent::__construct($username);
		$this->usuario = $usuario;
	}

	/**
	 * @return Usuario
	 */
	public function getUsuario() {
		return $this->usuario;
	}

	/**
     * {@inheritDoc}
     */
	public function getRoles() {
		$roles = parent::getRoles();
		
		if($this->usuario->isAdmin()) {
			$roles[] = "ROLE_SONATA_ADMIN";
		}

		return $roles;
	}
}