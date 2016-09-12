<?php
namespace AppBundle\Entity;

class DetalleTransaccion extends BaseEntity {

	CONST ESTADO_INICIO                    = 1;
	CONST ESTADO_NORMALIZAR_PERMISOS       = 2;
	CONST ESTADO_PERMISOS_NORMALIZADOS     = 3; 
	CONST ESTADO_NORMALIZAR_PERMISOS_ERROR = 4;

	private $estados = array(
		self::ESTADO_INICIO,
		self::ESTADO_NORMALIZAR_PERMISOS,
		self::ESTADO_PERMISOS_NORMALIZADOS,
		self::ESTADO_NORMALIZAR_PERMISOS_ERROR
	);
	
	/**
	 * @var null | int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $estado;

	/**
	 * @var string
	 */
	private $mensaje;	

	/**
	 * @var Transaccion
	 */	
	private $transaccion;

	/**
	 * @var Archivo | null
	 */
	private $archivo;

	/**
	 * @param int $estado
	 * @param string $mensaje
	 * @param Archivo $archivo
	 * @throws \InvalidArgumentException
	 */
	public function __construct($estado, $mensaje = '', Archivo $archivo = null) {
		$this->setEstado($estado);
		$this->mensaje = (string) $mensaje;
		$this->archivo = $archivo;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getEstado() {
		return $this->estado;
	}

	/**
	 * @param int
	 * @throws \InvalidArgumentException
	 */
	public function setEstado($estado) {
		$estado = (int) $estado;
		if(!in_array($estado, $this->estados)) {
			throw new \InvalidArgumentException(sprintf('estado %d no es válido', $estado));
		}
		$this->estado = $estado;
	}

	/**
	 * @return string
	 */
	public function getMensaje() {
		return $this->mensaje;
	}

	/**
	 * @param string
	 */
	public function setMensaje($mensaje) {
		$this->mensaje = (string) $mensaje;
	}

	/**
	 * @return Transaccion
	 */
	public function getTransaccion() {
		return $this->transaccion;
	}

	/**
	 * @param Transaccion $transaccion
	 */
	public function setTransaccion(Transaccion $transaccion) {
		$this->transaccion = $transaccion;
	}

	/**
	 * @param Archivo
	 */
	public function setArchivo(Archivo $archivo) {
		$this->archivo = $archivo;
	}

	/**
	 * @return Archivo|null
	 */
	public function getArchivo() {
		return $this->archivo;
	}
}