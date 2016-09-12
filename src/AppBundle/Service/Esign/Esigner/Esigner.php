<?php
namespace AppBundle\Service\Esign\Esigner;

use AppBundle\Service\Esign\Esigner\Client\ClientInterface;
use AppBundle\Service\Esign\Esigner\Entity\IntercambiaDoc;
use AppBundle\Service\Esign\Esigner\EsignerException;

class Esigner {
	/**
	 * @var ClientInterface
	 */
	private $client;

	public function __construct(ClientInterface $client) {
		$this->client = $client;
	}

	/**
	 * @param IntercambiaDoc $intercambiaDoc
	 * @return IntercambiaDocResult
	 * @throws EsignerException
	 */
	public function signDocument(IntercambiaDoc $intercambiaDoc) {
		$data = array(
			'Encabezado' => array(
				'User'                => $intercambiaDoc->getEncabezado()->getUser(),
				'Password'            => $intercambiaDoc->getEncabezado()->getPassword(),
				'NombreConfiguracion' => $intercambiaDoc->getEncabezado()->getNombreConfiguracion()
			),
			'Parametro'  => array(
				'Documento'       => $intercambiaDoc->getParametro()->getDocumento(),
				'NombreDocumento' => $intercambiaDoc->getParametro()->getNombreDocumento(),
				'MetaData'        => $intercambiaDoc->getParametro()->getMetaData()
			)
		);
		
		$intercambiaDocResult  = $this->client->signDocument($data);
		
		if($intercambiaDocResult->getEstado() != "OK") {
			throw new EsignerException($intercambiaDocResult->getComentarios());
		}
		
		return $intercambiaDocResult;
	}
}