<?php 
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Configuracion;
use AppBundle\Entity\Usuario;


class ConfiguracionRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = Configuracion::class;

	/**
	 * @param Usuario $usuario
	 * @param string $mimeType
	 * @return Configuracion | null
	 */
	public function findOneByUsuarioAndMimeType(Usuario $usuario, $mimeType) {
		$results = $this->entityManager->createQueryBuilder()
			->select('c')
            ->from(Configuracion::class, 'c')
            ->join('c.usuario', 'usuario')
            ->where('c.mimeType = :mimeType')
            ->andWhere('usuario.id = :idUsuario')
            ->setParameters(
            	array(
	                'mimeType'     => (string) $mimeType,
	                'idUsuario'    => $usuario->getId()
            ))->getQuery()->getResult();

        if($results) {
        	return $results[0];
        }
	}
}