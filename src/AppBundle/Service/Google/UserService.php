<?php
namespace AppBundle\Service\Google;
use AppBundle\Service\Google\Exception\NoUserIdException;

class UserService {

  /**
   * @var \Google_Service_Oauth2
   */
	private $oauth2;

  /**
   * @var \Google_Client
   * @var \Google_Service_Oauth2
   */
  public function __construct(\Google_Service_Oauth2 $oauth2) {
    $this->oauth2  = $oauth2;
  }

	/**
   * Send a request to the UserInfo API to retrieve the user's information.
   * @param string credentials OAuth 2.0 credentials to authorize the request.
   * @return Userinfo User's information.
   * @throws NoUserIdException An error occurred
   * @throws \Google_Exception
  */
  public function getUserInfo($credentials) {
    $this->oauth2->getClient()->setAccessToken($credentials);
    $userInfo = null;
    $userInfo = $this->oauth2->userinfo->get();
    	
    if ($userInfo != null && $userInfo->getId() != null) {
      return $userInfo;
    }

    throw new NoUserIdException();
  }
  
}