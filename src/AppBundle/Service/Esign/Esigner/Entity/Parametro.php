<?php
namespace AppBundle\Service\Esign\Esigner\Entity;

class Parametro {

	/**
	 * @var string
	 */ 
	private $documento;

	/**
	 * @var string
	 */ 
	private $nombreDocumento;
	
	/**
	 * @var string|null
	 */ 	
	private $metaData;

	/**
	 * @param string $documento
	 * @param string $nombreDocumento
	 * @param null | string $metaData 
	 */
	public function __construct($documento, $nombreDocumento, $metaData = null) {
		$this->documento = (string) $documento;
		$this->nombreDocumento = (string) $nombreDocumento;
		$this->metaData = is_null($metaData) ? null : (string) $metaData;
	}

	/**
	 * @return string
	 */
	public function getDocumento() {
		return $this->documento;
	}

	/**
	 * @return string
	 */
	public function getNombreDocumento() {
		return $this->nombreDocumento;
	}

	/**
	 * @return string
	 */
	public function getMetaData() {
		return $this->metaData;
	}
}