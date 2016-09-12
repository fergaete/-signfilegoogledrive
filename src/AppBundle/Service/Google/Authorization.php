<?php
namespace AppBundle\Service\Google;

use AppBundle\Service\Google\Credential\FileSystemImplementation;
use AppBundle\Service\Google\Exception\CodeExchangeException;
use AppBundle\Service\Google\Exception\NoUserIdException;
use AppBundle\Service\Google\Exception\NoRefreshTokenException;
use AppBundle\Service\Google\Credential\CredentialInterface;

class Authorization {

    private $scopes = array(
        'email', 
        'profile', 
        'https://www.googleapis.com/auth/drive', 
        'https://www.googleapis.com/auth/drive.install'
    );
    private $client;

    public function __construct(\Google_Client $client) {
      $this->client = $client;
    }

    /**
     * Exchange an authorization code for OAuth 2.0 credentials.
     * @param String $authorizationCode Authorization code to exchange for OAuth 2.0 credentials.
     * @return String Json representation of the OAuth 2.0 credentials.
     * @throws CodeExchangeException An error occurred.
     */
    public function exchangeCode($authorizationCode) {
      return $this->client->authenticate($authorizationCode);
    }

    /**
     * Retrieve the authorization URL.
     *
     * @param String $emailAddress User's e-mail address.
     * @param String $state State for the authorization URL.
     * @return String Authorization URL to redirect the user to.
     */
    public function getAuthorizationUrl($emailAddress, $state) {
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
        $this->client->setState($state);
        $this->client->setScopes($this->scopes);
        $tmpUrl = parse_url($this->client->createAuthUrl());
        $query = explode('&', $tmpUrl['query']);
        $query[] = 'user_id=' . urlencode($emailAddress);
        
        return $tmpUrl['scheme'] . '://' . $tmpUrl['host'] . $tmpUrl['path'] . '?' . implode('&', $query);
    }

    /**
     * Retrieve credentials using the provided authorization code.
     *
     * This function exchanges the authorization code for an access token and
     * queries the UserInfo API to retrieve the user's e-mail address. If a
     * refresh token has been retrieved along with an access token, it is stored
     * in the application database using the user's e-mail address as key. If no
     * refresh token has been retrieved, the function checks in the application
     * database for one and returns it if found or throws a NoRefreshTokenException
     * with the authorization URL to redirect the user to.
     *
     * @param String authorizationCode Authorization code to use to retrieve an access
     *                                 token.
     * @param String state State to set to the authorization URL in case of error.
     * @return String Json representation of the OAuth 2.0 credentials.
     * @throws NoRefreshTokenException No refresh token could be retrieved from
     *         the available sources.
     */
    public function getCredentials($authorizationCode, $state, CredentialInterface $credentialService) {
      $emailAddress = '';
      try {
        
        $credentials = $this->exchangeCode($authorizationCode);
        $userService = new UserService(new \Google_Service_Oauth2($this->client));
        $userInfo = $userService->getUserInfo($credentials);
        
        $emailAddress = $userInfo->getEmail();
        $userId = $userInfo->getId();

        $credentialsArray = json_decode($credentials, true);
        
        if(isset($credentialsArray['refresh_token'])) {
          $credentialService->storeCredentials($userId, $credentials);
          return $credentials;
        } 
        else {
          $credentials = $credentialService->getStoredCredentials($userId);
          $credentialsArray = json_decode($credentials, true);
          if ($credentials != null && isset($credentialsArray['refresh_token'])) {
            return $credentials;
          }
        }
      } 
      catch (CodeExchangeException $e) {
        print 'An error occurred during code exchange.';
        // Drive apps should try to retrieve the user and credentials for the current
        // session.
        // If none is available, redirect the user to the authorization URL.
        $e->setAuthorizationUrl($this->getAuthorizationUrl($emailAddress, $state));
        throw $e;
      } 
      catch (NoUserIdException $e) {
        print 'No e-mail address could be retrieved.';
      }
      
      // No refresh token has been retrieved.
      $authorizationUrl = $this->getAuthorizationUrl($emailAddress, $state);
      throw new NoRefreshTokenException($authorizationUrl);
    }
}
