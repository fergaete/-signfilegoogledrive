<?php
namespace AppBundle\Service\Esign\Esigner\Client;
use AppBundle\Service\Esign\Esigner\Entity\IntercambiaDoc;
use AppBundle\Service\Esign\Esigner\Entity\IntercambiaDocResponse;
use AppBundle\Service\Esign\Esigner\EsignerException;

interface ClientInterface {

	/**
	 * @param array $requestData
	 * @return IntercambiaDocResult
	 * @throws EsignerException
	 **/
	public function signDocument(array $requestData);
}