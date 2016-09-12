<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Monolog\Logger;

class UsuarioLogAdmin extends BaseAdmin {   

	public $baseRouteName = 'historial';
    public $baseRoutePattern = '/historial';

    protected $datagridValues = array( 
        '_page'       => 1, 
        '_sort_order' => 'DESC', 
        '_sort_by'    => 'createdAt' 
    );

	/**
	 * @param RouteCollection $collection
	 */
	protected function configureRoutes(RouteCollection $collection) {
    	$collection->clearExcept(array('list','export'));
	}

	/**
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
			->add('usuario')
			->add('message', null, array(
				'label' => 'Mensaje'
			))
			->add(
				'level', 
				'doctrine_orm_choice', 
				array('label' => 'Nivel'), 
				'choice', 
				array(
					'choices' => $this->getLevels()
				)
			)
			->add(
				'createdAt', 
				'doctrine_orm_datetime_range',
				array('label' => 'Fecha Creación :'), 
				null, 
				array(
					'widget' 	=> 'single_text',
					'format' 	=> 'yyyy-MM-dd HH:mm:ss',
					'required' 	=> false, 
					'attr' 		=> array(
						'class' => 'datetimepicker'
					)
				)
			);
    }
	
	/**
	 * @param ListMapper $listMapper
	 */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
			->addIdentifier('usuario', null, array(
                'route' => array(
                    'name' => 'show'
                )
            ))
            ->add('message', null, array(
            	'label' => 'Mensaje'
            ))
			->add('level', 'choice', array(
				'label'		=> 'Nivel',
				'choices' 	=> $this->getLevels()
			))
			->add('createdAt', 'datetime', array(
			'label' 	=> 'Fecha Creación',
			'pattern'   => 'dd MMMM y, HH:mm:ss'
		));
    }
    
    /**
     * @return array
     */
    public function getExportFields() {
        return array(
            'Identificador'  => 'id',
            'Usuario'        => 'usuario.email',
            'Mensaje'        => 'message',
            'Nivel'          => 'level',
            'Fecha Creación' =>'createdAt'
        );
    }

    /**
     * @return array
     */
    private function getLevels() {
    	return array_flip(Logger::getLevels());
    }
}