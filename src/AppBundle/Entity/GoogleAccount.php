<?php
namespace AppBundle\Entity;

class GoogleAccount extends BaseEntity {
	
	/**
	 * @var int
	 */
	private $id;

	/**
     * @var string
     */
	private $userId;
	
	/**
     * @var string
     */
	private $credential;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @var string $userId
	 */
	public function setUserId($userId) {
		$this->userId = (string) $userId;
	}

	/**
	 * @return string 
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @var string $credential
	 */
	public function setCredential($credential) {
		$this->credential = (string) $credential;
	}

	/**
	 * @return string 
	 */
	public function getCredential() {
		return $this->credential;
	}
}