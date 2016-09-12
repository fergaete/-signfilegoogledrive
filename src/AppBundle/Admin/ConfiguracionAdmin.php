<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use AppBundle\Entity\Configuracion;

class ConfiguracionAdmin extends BaseAdmin {
	
	/**
	 * @param ShowMapper $showMapper
	 */
	protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper->with('Información General')
            ->add('nombre')
			->add('mimeType', 'choice', array(
				'label' 	=> 'MimeType',
				'choices' 	=> Configuracion::$mimeTypes
			))
		->end()
		->with('Información de Usuario')
			->add('usuario', null, array(
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
        $formMapper->add('nombre', null, array('label'=>'Configuración Firma Esigner'))
		->add('mimeType', 'choice', array(
			'label'		=> 'MimeType',
			'choices' 	=> Configuracion::$mimeTypes
		))
		->add('usuario');
    }
	
	/**
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('nombre')
		->add('mimeType', 'doctrine_orm_choice', array('label' => 'MimeType'), 'choice', array(
			'choices' => Configuracion::$mimeTypes
		))
		->add('usuario');
		parent::configureDatagridFilters($datagridMapper);
    }
	
	/**
	 * @param ListMapper $listMapper
	 */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('nombre', null, array(
			'route' => array(
				'name' => 'show'
			)
		))
		->add('mimeType', 'choice', array(
			'label'		=> 'MimeType',
			'choices' 	=> Configuracion::$mimeTypes
		))
		->add('usuario');
		parent::configureListFields($listMapper);
    }
    
    /**
     * @return array
     */
    public function getExportFields() {
        return array(
            'Identificador'         => 'id',
            'Usuario'               => 'usuario.email',
            'Configuracion'         => 'nombre',
            'Tipo Archivo'          => 'mimeType',
            'Fecha Creación'        =>'createdAt',
            'Fecha Actualización'   =>'updatedAt',
        );
    }
}