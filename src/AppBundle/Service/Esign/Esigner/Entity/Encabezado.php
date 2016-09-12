<?php
namespace AppBundle\Service\Esign\Esigner\Entity;

class Encabezado {

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 **/
	private $password;

	/**
	 * @var string
	 */
	private $nombreConfiguracion;

	/**
	 * @param string $user
	 * @param string $password
	 * @param string $nombreConfiguracion
	 */
	public function __construct($user, $password, $nombreConfiguracion) {
		$this->user = (string) $user;
		$this->password = (string) $password;
		$this->nombreConfiguracion = (string) $nombreConfiguracion;
	}

	/**
	 * @return string
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getNombreConfiguracion() {
		return $this->nombreConfiguracion;
	}
}