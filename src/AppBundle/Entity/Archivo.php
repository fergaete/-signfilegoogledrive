<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Collection\DetalleTransaccionCollection;

class Archivo extends BaseEntity {
	
	CONST TIPO_ENTRADA = 1;
	CONST TIPO_SALIDA  = 2;
	
	private $tipos = array(
		self::TIPO_ENTRADA,
		self::TIPO_SALIDA
	);

	/**
	 * @var null | int
	 */
	private $id;
	
	/**
	 * @var string
	 */
	private $googleFileId;

	/**
	 * @var string
	 */
	private $nombre;
	
	/**
	 * @var string
	 */
	private $mimeType;

	/**
	 * @var int
	 */
	private $tipo;

	/**
	 * @var boolean
	 */
	private $esPublico;

	/**
	 * @var detalleTransaccionCollection
	 */
	private $detalleTransacciones;

	/**
	 * @param string $googleFileId
	 * @param string $nombre
	 * @param string $mimeType
	 * @param bool $esPublico
	 */
	public function __construct($googleFileId, $nombre, $mimeType, $esPublico) {
		$this->detalleTransacciones = new DetalleTransaccionCollection();	
		$this->googleFileId  = (string) $googleFileId;
		$this->setNombre($nombre);
		$this->setMimeType($mimeType);
		$this->setEsPublico($esPublico);
		$this->tipo = self::TIPO_ENTRADA;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getGoogleFileId() {
		return $this->googleFileId;
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
	 * @param string
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

	/**
	 * @param int $tipo
	 * @throws \InvalidArgumentException
	 */
	public function setTipo($tipo) {
		$tipo = (int) $tipo;
		if(!in_array($tipo, $this->tipos)) {
			throw new \InvalidArgumentException(sprintf("tipo %d no es válido", $tipo));
		}

		$this->tipo = $tipo;
	}

	/**
	 * @return int
	 */
	public function getTipo() {
		return $this->tipo;
	}

	/**
	 * @oaram bool $esPublico
	 */
	public function setEsPublico($esPublico) {
		$this->esPublico = (bool) $esPublico;
	}

	/**
	 * @return bool
	 */
	public function esPublico() {
		return $this->esPublico;
	}
}