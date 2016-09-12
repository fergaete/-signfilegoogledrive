<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Collection\UsuarioCollection;

class Cliente extends BaseEntity {
	
	/**
	 * @var null | int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $nombre;

	/**
	 * @var string
	 */
	private $wsdl;

	/**
	 * @var UsuarioCollection
	 */
	private $usuarios;

	public function __construct() {
		$this->usuarios = new UsuarioCollection();
	}

	/**
	 * @param string $nombre
	 */
	public function setNombre($nombre) {
		$this->nombre = (string) $nombre;
	}

	/**
	 * @return string
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * @param string $wsdl
	 */
	public function setWsdl($wsdl) {
		$this->wsdl = (string) $wsdl;
	}

	/**
	 * @return string
	 */
	public function getWsdl() {
		return $this->wsdl;
	}

	/**
	 * @return UsuarioCollection
	 */
	public function getUsuarios() {
		return $this->usuarios;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->nombre;
	}
}