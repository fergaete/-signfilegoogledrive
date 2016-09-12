<?php
namespace AppBundle\Service\Log\Processor;

use Symfony\Bridge\Monolog\Processor\WebProcessor;

use AppBundle\Service\OAuth\OAuthUser;

class RequestProcessor extends WebProcessor {

    private $tokenStorage;

    public function __construct($tokenStorage) {
    	$this->tokenStorage = $tokenStorage;    
    }

    public function processRecord(array $record) {
    	$token = $this->tokenStorage->getToken();

    	if($token && $token->getUser() instanceof OAuthUser) {
    		$record['extra']['usuario'] = $token->getUser()->getUsuario(); 
    	}

    	return $record;
    }
}