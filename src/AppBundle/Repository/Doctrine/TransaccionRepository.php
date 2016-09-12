<?php
namespace AppBundle\Repository\Doctrine;

use AppBundle\Entity\Transaccion;
use AppBundle\Entity\Usuario;

class TransaccionRepository extends BaseRepository {
	/**
	 * @var string
	 */
	protected $entityName = Transaccion::class;

	/**
	 * @param $id
	 * @param Usuario $usuario
	 */
	public function findOneByIdAndUsuario($id, Usuario $usuario) {
		$results = $this->entityManager->createQueryBuilder()
			->select('t')
            ->from(Transaccion::class, 't')
            ->join('t.usuario', 'usuario')
            ->where('t.id = :idTransaccion')
            ->andWhere('usuario.id = :idUsuario')
            ->setParameters(
            	array(
            		'idTransaccion' => (int) $id,
            		'idUsuario' 	=> $usuario->getId()
            ))->getQuery()->getResult();

        if($results) {
        	return $results[0];
        }
	}
}