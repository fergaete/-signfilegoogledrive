<?php
namespace AppBundle\Service\Esign\Esigner\Client;
use AppBundle\Service\Esign\Esigner\Entity\IntercambiaDocResult;
use AppBundle\Service\Esign\Esigner\EsignerException;

class SoapClient implements ClientInterface {
	
	/**
	 * @var \SoapClient
	 */
	private $client;
	
	/**
	 * @param string $wsdl
	 * @param array $options
	 */
	public function __construct($wsdl, $options = array()) {
		$this->client = new \SoapClient($wsdl, $options);
	}

	/**
	 * @param array $requestData
	 * @return IntercambiaDocResult
	 * @throws EsignerException
	 **/
	public function signDocument(array $requestData) {
		try {
			
			$response = $this->client->intercambiaDoc($requestData);
			$result = $response->intercambiaDoc->IntercambiaDocResult;
			
			$intercambiaDocResult = new IntercambiaDocResult();
			$intercambiaDocResult->setEstado($result->Estado);
			$intercambiaDocResult->setComentarios($result->Comentarios);
			$intercambiaDocResult->setFormatoDocumento($result->FormatoDocumento);
			$intercambiaDocResult->setNombreDocumento($result->NombreDocumento);
			$intercambiaDocResult->setDocumento($result->Documento);

			return $intercambiaDocResult;
		}
		catch(\Exception $ex) {
			throw new EsignerException($ex);
		}
	}
}