<?php
namespace AppBundle\Service\Esign\Esigner\Entity;

class IntercambiaDoc {
	/**
	 * @var Encabezado
	 **/
	private $encabezado;

	/**
	 * @var Parametro
	 **/
	private $parametro;

	public function __construct(Encabezado $encabezado, Parametro $parametro) {
		$this->encabezado = $encabezado;
		$this->parametro  = $parametro;
	}

	/**
	 * @return Encabezado
	 */
	public function getEncabezado() {
		return $this->encabezado;
	}

	/**
	 * @return Parametro
	 **/
	public function getParametro() {
		return $this->parametro;
	}
}