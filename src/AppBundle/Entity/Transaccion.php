<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Collection\DetalleTransaccionCollection;

class Transaccion extends BaseEntity {
	
	/**
	 * @var null | int
	 */
	private $id;

	/**
	 * @var Usuario
	 */	
	private $usuario;

	/**
	 * @var DetalleTransaccionCollection
	 */
	private $detalles;

	public function __construct() {
		$this->detalles = new DetalleTransaccionCollection();
	} 

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return Usuario
	 */
	public function getUsuario() {
		return $this->usuario;
	}

	/**
	 * @param Usuario $usuario
	 */
	public function setUsuario(Usuario $usuario) {
		$this->usuario = $usuario;
	}

	/**
	 * @return DetalleTransaccionCollection
	 */
	public function getDetalles() {
		return $this->detalles;
	}
}