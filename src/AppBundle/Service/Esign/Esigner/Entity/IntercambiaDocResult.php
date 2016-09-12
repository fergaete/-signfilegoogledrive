<?php
namespace AppBundle\Service\Esign\Esigner\Entity;

class IntercambiaDocResult {
	
	private $estado;
	private $comentarios;
	private $formatoDocumento;
	private $nombreDocumento;
	private $idAlfresco;
	private $documento;

	/**
	* @return string
	*/
	public function getEstado() {
		return $this->estado;
	}

	/**
	* @return string
	*/
	public function getComentarios() {
		return $this->comentarios;
	}

	/**
	* @return string
	*/
	public function getFormatoDocumento() {
		return $this->formatoDocumento;
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
	public function getIdAlfresco() {
		return $this->idAlfresco;
	}

	/**
	* @return string
	*/
	public function getDocumento() {
		return $this->documento;
	}

	/**
	* @param string $estado
	*/
	public function setEstado($estado) {
		$this->estado = (string) $estado;
	}

	/**
	* @param string $comentarios
	*/
	public function setComentarios($comentarios) {
		$this->comentarios = (string) $comentarios;
	}

	/**
	* @param string $formatoDocumento
	*/
	public function setFormatoDocumento($formatoDocumento) {
		$this->formatoDocumento = (string) $formatoDocumento;
	}

	/**
	* @param string $nombreDocumento
	*/
	public function setNombreDocumento($nombreDocumento) {
		$this->nombreDocumento = (string) $nombreDocumento;
	}

	/**
	* @param string $idAlfresco
	*/
	public function setIdAlfresco($idAlfresco) {
		$this->idAlfresco = (string) $idAlfresco;
	}

	/**
	* @param string $documento
	*/
	public function setDocumento($documento) {
		$this->documento = (string) $documento;
	}

}