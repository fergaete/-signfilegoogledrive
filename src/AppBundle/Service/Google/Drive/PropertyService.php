<?php
namespace AppBundle\Service\Google\Drive;

class PropertyService {

	const VISIBILITY_PUBLIC  = 'PUBLIC';
	const VISIBILITY_PRIVATE = 'PRIVATE';
	const KEY_FECHA_FIRMA    = 'fechaFirma';
	const KEY_USUARIO_FIRMA  = 'usuarioFirma';

	/**
	 * @var \Google_Service_Drive
	 */
	private $service;

	/**
	 * @param \Google_Service_Drive $service
	 */
	public function __construct(\Google_Service_Drive $service) {
		$this->service = $service;
	}

	/**
	 * @param \Google_Service_Drive_Property $property
	 * @param string $fileId
	 * @throws DriveException
	 */
	public function insert(\Google_Service_Drive_Property $property, $fileId) {
		try {
			$this->service->properties->insert($fileId, $property);
		}
		catch(\Exception $ex) {
			throw new DriveException($ex);
		}
	}

	/**
	 * @param string $fileId
	 * @param string $key
	 * @return Google_Service_Drive_Property | null
	 */
	public function get($fileId, $key) {
		try {
			return $this->service->properties->get($fileId, $key, array('visibility' => self::VISIBILITY_PUBLIC));
		}
		catch(\Exception $ex) {
			if($ex->getCode() == 404) {
				return null;
			}
			throw new DriveException($ex);
		}
	}
}