<?php

namespace Rz\ClassificationBundle\Admin;

use Sonata\ClassificationBundle\Admin\CollectionAdmin as BaseClass;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CollectionAdmin extends BaseClass
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name',null, array('footable'=>array('attr'=>array('data_toggle'=>true))))
            ->add('context', null,  array('footable'=>array('attr'=>array('data_hide'=>'phone'))))
            ->add('enabled', null, array('editable' => true, 'footable'=>array('attr'=>array('data_hide'=>'phone'))))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'Show' => array('template' => 'SonataAdminBundle:CRUD:list__action_show.html.twig'),
                    'Edit' => array('template' => 'SonataAdminBundle:CRUD:list__action_edit.html.twig'),
                    'Delete' => array('template' => 'SonataAdminBundle:CRUD:list__action_delete.html.twig')),
                'footable'=>array('attr'=>array('data_hide'=>'phone,tablet')),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('enabled', null, array('required' => false))
            ->add('context')
            ->add('name')
            ->add('description', 'textarea', array('required' => false))
            ->add('content', 'sonata_formatter_type', array(
                'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                'format_field'   => 'contentFormatter',
                'source_field'   => 'rawContent',
                'ckeditor_context' => 'news',
                'source_field_options'      => array(
                    'attr' => array('class' => 'span12', 'rows' => 20)
                ),
                'target_field'   => 'content',
                'listener'       => true,
            ))
        ;

        if (interface_exists('Sonata\MediaBundle\Model\MediaInterface')) {
            $formMapper->add('media', 'sonata_type_model_list',
                array('required' => false),
                array(
                    'link_parameters' => array(
                        'provider' => 'sonata.media.provider.image',
                        'context'  => 'sonata_collection'
                    )
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('description')
            ->add('enabled')
            ->add('slug')
            ->add('contentFormatter')
            ->add('content')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}