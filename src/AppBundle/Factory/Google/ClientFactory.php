<?php
namespace AppBundle\Factory\Google;

class ClientFactory {
	
	/**
	 * @param string $clientId
	 * @param string $clientSecret
	 * @param string $redirectUrl
	 * @return \Google_Client
	 */
	public static function getInstance($clientId, $clientSecret, $redirectUrl) {
		$client = new \Google_Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);
        return $client;
	}
}