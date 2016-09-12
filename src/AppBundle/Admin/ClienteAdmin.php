<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class ClienteAdmin extends BaseAdmin {
    
    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('export');
	}
	
	/**
	 * @param ShowMapper $showMapper
	 */
	protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper->with('Información General')
            ->add('nombre')
			->add('wsdl', null, array('label' => 'URL WSDL Esigner'))
		->end()
		->with('Información de Usuarios')
			->add('usuarios', null, array(
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
			->add('nombre')
			->add('wsdl', null, array(
				'label'    => 'URL WSDL Esigner',
				'required' => false
			))
        ->end();
    }
	
	/**
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
			->add('nombre')
			->add('wsdl');
		parent::configureDatagridFilters($datagridMapper);
    }
	
	/**
	 * @param ListMapper $listMapper
	 */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
			->addIdentifier('nombre', null, array(
                'route' => array(
                    'name' => 'show'
                )
            ))
			->add('wsdl');
		parent::configureListFields($listMapper);
    }
	
	/**
	 * @param ErrorElement $errorElement
	 * @param $object
	 */
    public function validate(ErrorElement $errorElement, $object) {
        $errorElement->with('wsdl')
            ->assertUrl()
        ->end();
    }
}