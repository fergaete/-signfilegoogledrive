<?php
namespace AppBundle\Service\Log\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DatabaseHandler extends AbstractProcessingHandler {

	private $container;

	public function setContainer($container) {
		$this->container = $container;
	}

    /**
     * {@inheritdoc}
     */
    protected function write(array $record) {
    	if($record['channel'] == 'doctrine' && (int)$record['level'] >= Logger::INFO) {
            error_log($record['message']);
            return;
        }

    	if((int)$record['level'] >= Logger::INFO) {
	    	try {
	    		$sql = "
	    			INSERT INTO usuario_log(id, id_usuario, message, level, created_at, updated_at)
    				VALUES (NULL, :id_usuario, :message, :level, :createdAt, NULL)";
    	
	    		$em = $this->container->get('doctrine')->getManager();
	    		$statement = $em->getConnection()->prepare($sql);
	    		$statement->execute(array(
	    			':id_usuario' => isset($record['extra']['usuario']) ? $record['extra']['usuario']->getId() : null, 
	    			':message'    => $record['message'],
	    			':level'      => $record['level'],
	    			':createdAt'  => date('Y-m-d H:i:s')
	    		));
	    	}
	    	catch(\Exception $ex) {
	    		error_log($record['message']);
	            error_log($e->getMessage());
	    	}
	    }
    }
}