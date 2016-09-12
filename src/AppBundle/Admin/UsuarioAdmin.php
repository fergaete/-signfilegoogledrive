<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class UsuarioAdmin extends BaseAdmin {

	/**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection) {
		$collection->add('log', $this->getRouterIdParameter().'/log');
        $collection->remove('export');
	}
	
	/**
	 * @param ShowMapper $showMapper
	 */
	protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper->with('Información General')
            ->add('email')
			->add('username')
			->add('isAdmin', null, array(
				'label' => 'Adminitrador'
			))
		->end()
		->with('Información de Cliente')
			->add('cliente', null, array(
				'route' => array(
                    'name' => 'show'
                )
			))
		->end()
		->with('Información de Configuraciones')
			->add('configuraciones', null, array(
				'route' => array(
                    'name' => 'show'
                )
			))
		->end();
		parent::configureShowFields($showMapper);
    }
	
	/**
	 * @param FormMapper $formMapper
	 */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->with('Información General')
			->add('email', null, array('label'=>'Email (OAuth)'))
			->add('username', null, array('label'=>'Username (Esigner)'))
			->add('cliente')
        ->end();
    }
	
	/**
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
			->add('email')
			->add('username')
			->add('isAdmin', null, array('label' => 'Adminitrador'))
			->add('cliente');
		parent::configureDatagridFilters($datagridMapper);
    }
	
	/**
	 * @param ListMapper $listMapper
	 */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
			->addIdentifier('email', null, array(
                'route' => array(
                    'name' => 'show'
                )
            ))
			->add('username')
			->add('isAdmin', 'boolean', array('label' => 'Administrador'))
			->add('cliente', null, array(
				'route' => array(
                    'name' => 'show'
                )
			));
			
			parent::configureListFieldsDates($listMapper);

			$listMapper->add('_action', 'actions', array(
				'label' => "Acciones",
				'actions' => array(
					'log' => array(
						'template' 	=> 'AppBundle:CRUD:list__action_log.html.twig', 
						'label' 	=> null
					),
					'show' => array(
						'template' 	=> 'AppBundle:CRUD:list__action_show.html.twig', 
						'label' 	=> null
					),
					'edit' => array(
						'template' 	=> 'AppBundle:CRUD:list__action_edit.html.twig',
						'label' 	=> null
					),
					'delete' => array(
						'template' 	=> 'AppBundle:CRUD:list__action_delete.html.twig',
						'label' 	=> null					
					)
				)
			));
    }
	
	/**
	 * @param ErrorElement $errorElement
	 * @param $object
	 */
    public function validate(ErrorElement $errorElement, $object) {
        $errorElement->with('email')
            ->assertEmail()
        ->end();
    }
}