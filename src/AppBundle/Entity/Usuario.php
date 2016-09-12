<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\Collection\UsuarioLogCollection;
use AppBundle\Entity\Collection\ConfiguracionCollection;
use AppBundle\Entity\Collection\TransaccionCollection;

class Usuario extends BaseEntity implements UserInterface {
	
	/**
	 * @var null | int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $username;

	/**
     * @var string
     */
    private $password;
    
    /**
	 * @var GoogleAccount
	 */
	private $googleAccount;

	/**
     * @var ConfiguracionCollection
     */
	private $configuraciones;

	/**
	 * @var bool
	 */
	private $isAdmin;

	/**
	 * @var Cliente
	 */
	private $cliente;

	/**
	 * @var UsuarioLogCollection
	 */
	private $logs;

	/**
	 * @var TransaccionCollection
	 */
	private $transacciones;
	
	public function __construct() {
		$this->isAdmin = false;
		$this->configuraciones = new ConfiguracionCollection();
		$this->logs = new UsuarioLogCollection();
		$this->transacciones = new TransaccionCollection();
	}

	/**
	 * @return null | int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param string
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}    
	
	/**
	 * @param string
	 */
	public function setUsername($username) {
		$this->username = $username;
	}
    
    /**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}    
	
	/**
	 * @param string
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return ConfiguracionCollection
	 */
	public function getConfiguraciones() {
		return $this->configuraciones;
	}

	/**
	 * @return GoogleAccount
	 */
	public function getGoogleAccount() {
		return $this->googleAccount;
	}

	/**
	 * @param GoogleAccount $googleAccount
	 */
	public function setGoogleAccount($googleAccount) {
		$this->googleAccount = $googleAccount;
	}

	/**
	 * @param bool $isAdmin
	 */
	public function setIsAdmin($isAdmin) {
		$this->isAdmin = (bool) $isAdmin;
	}

	/**
	 * @return bool
	 */
	public function isAdmin() {
		return $this->isAdmin;
	}

	/**
	 * @param Cliente $cliente
	 */
	public function setCliente(Cliente $cliente) {
		$this->cliente = $cliente;
	}


	/**
	 * @return Cliente | null
	 */
	public function getCliente() {
		return $this->cliente;
	}
	
	/**
	 * @return UsuarioLogCollection
	 */
	public function getLogs() {
		return $this->logs;
	}

	/**
	 * @return TransaccionCollection
	 */
	public function getTransacciones() {
		return $this->transacciones;
	}

	/**
	 * @var Transaccion $transaccion
	 */ 
	public function addTransaccion(Transaccion $transaccion) {
		$this->transacciones->add($transaccion);
	}

	/*
	 * @return string
	 */
	public function __toString() {
		return $this->email;
	}
    
    /**
     * @return null
     */
    public function getSalt() {
        //TODO: Extraer a capa de persistencia
        return '1234567890*';
    }
    
    /**
     * @return array
     */
    public function getRoles() {
        return array('ROLE_USER', 'ROLE_OAUTH_USER', 'ROLE_SONATA_ADMIN');
    }
    
    /**
     * @return null
     */
    public function eraseCredentials() {}
}