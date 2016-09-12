<?php
namespace AppBundle\Entity;

class UsuarioLog extends BaseEntity {
    
    /**
     * @var null | int
     */
    private $id;

    /**
     * @var Usuario
     */ 
    private $usuario;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $level;
	
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}
	
	/**
	 * @return Usuario
	 */
	public function getUsuario() {
		return $this->usuario;
	}
}