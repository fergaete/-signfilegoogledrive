<?php
namespace AppBundle\Entity;

class Configuracion extends BaseEntity {
	
	const MIME_TYPE_DOC 	= 'application/doc';
	const MIME_TYPE_DOCX	= 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	const MIME_TYPE_PDF 	= 'application/pdf';
	const MIME_TYPE_XML		= 'application/xml';
	
	/**
	 * @var array
	 */
	public static $mimeTypes = array(
		self::MIME_TYPE_DOC  => 'DOC',
		self::MIME_TYPE_DOCX => 'DOCX',
		self::MIME_TYPE_PDF  => 'PDF',
		self::MIME_TYPE_XML  => 'XML'
	);
	
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
	private $mimeType;

	/**
	 * @var Usuario
	 */
	private $usuario;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
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
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = (string) $mimeType;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}
	
	/*
	 * @return Usuario
	 */
	public function getUsuario() {
		return $this->usuario;
	}
	
	/*
	 * @param Usuario $usuario
	 */
	public function setUsuario(Usuario $usuario) {
		$this->usuario = $usuario;
	}

	/*
	 * @return String
	 */
	public function __toString() {
		return $this->nombre;
	}
}