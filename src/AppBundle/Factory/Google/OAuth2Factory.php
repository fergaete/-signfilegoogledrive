<?php
namespace AppBundle\Factory\Google;
class OAuth2Factory {
	
	/**
	 * @param \Google_Client $client
	 * @return \Google_Service_Oauth2
	 */
	public static function getInstance(\Google_Client $client) {
		return new \Google_Service_Oauth2($client);
	}
}