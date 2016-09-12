<?php
namespace AppBundle\Service\Google\Exception;

class GetCredentialsException extends \Exception {
	/**
	 * @var string
	 */
	protected $authorizationUrl;

  /**
  * @param authorizationUrl The authorization URL to redirect the user to.
  */
  public function __construct($authorizationUrl) {
    $this->authorizationUrl = $authorizationUrl;
  }

	/**
 	 * @return the authorizationUrl.
 	 */
	public function getAuthorizationUrl() {
  	return $this->authorizationUrl;
	}

	/**
   * Set the authorization URL.
   */
	public function setAuthorizationurl($authorizationUrl) {
  	$this->authorizationUrl = $authorizationUrl;
	}
  
}