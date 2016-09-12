<?php
namespace AppBundle\Factory\Google;
class DriveFactory {
	
	/**
	 * @var \Google_Client $client
	 * @return \Google_Service_Drive
	 */
	public static function getInstance(\Google_Client $client) {
		return new \Google_Service_Drive($client);
	}
}