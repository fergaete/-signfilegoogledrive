<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

abstract class BaseAdmin extends Admin {
	
	/**
	 * @param ShowMapper $showMapper
	 */
	protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper->with('Información de Sistema')
			->add('createdAt', 'datetime', array(
				'label' 	=> 'Fecha Creación',
				'pattern'   => 'dd MMMM y, HH:mm:ss'
			))
			->add('updatedAt', 'datetime', array(
				'label' 	=> 'Fecha Actualización',
                'pattern'   => 'dd MMMM y, HH:mm:ss'
			))
		->end();
    }
	
	/**
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('createdAt', 'doctrine_orm_datetime_range',
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
		)
		->add('updatedAt', 'doctrine_orm_datetime_range', 
			array('label' => 'Fecha Actualización :'), 
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
        $this->configureListFieldsDates($listMapper);

		$listMapper->add('_action', 'actions', array(
			'label' => "Acciones",
			'actions' => array(
				'show' 		=> array(
					'template' 	=> 'AppBundle:CRUD:list__action_show.html.twig', 
					'label' 	=> null
				),
				'edit' 		=> array(
					'template' 	=> 'AppBundle:CRUD:list__action_edit.html.twig',
					'label' 	=> null
				),
				'delete' 	=> array(
					'template' 	=> 'AppBundle:CRUD:list__action_delete.html.twig',
					'label' 	=> null					
				),
			)
		));
    }

    /**
	 * @param ListMapper $listMapper
	 */
    protected function configureListFieldsDates(ListMapper $listMapper) {
    	 $listMapper->add('createdAt', 'datetime', array(
			'label' 	=> 'Fecha Creación',
			'pattern'   => 'dd MMMM y, HH:mm:ss'
		))
		->add('updatedAt', 'datetime', array(
			'label' 	=> 'Fecha Actualización',
			'pattern'   => 'dd MMMM y, HH:mm:ss'
		));
    }
}