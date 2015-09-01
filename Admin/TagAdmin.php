<?php

namespace Rz\ClassificationBundle\Admin;

use Sonata\ClassificationBundle\Admin\TagAdmin as BaseAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Rz\ClassificationBundle\Provider\TagPool;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagAdmin extends BaseAdmin
{
    const TAG_DEFAULT_CONTEXT = 'default';
    protected $contextManager;
    protected $pool;

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('footable'=>array('attr'=>array('data_toggle'=>true))))
            ->add('enabled', null, array('editable' => true, 'footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
            ->add('createdAt', null, array('footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
            ->add('updatedAt', null, array('footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
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
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('slug')
            ->add('enabled')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        if ($contextId = $this->getPersistentParameter('context')) {
            $context = $this->contextManager->find($contextId);

            if (!$context) {
                $context = $this->contextManager->create();
                $context->setEnabled(true);
                $context->setId($context);
                $context->setName($context);

                $this->contextManager->save($context);
            }

            $instance->setContext($context);
        }

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('enabled')
            ->add('context')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $defaultContext = $this->contextManager->find('default');

        if (!$defaultContext) {
            throw new NotFoundHttpException('Default context should be defined');
        }

        $parameters = array(
            'context'      => $defaultContext->getId(),
            'hide_context' => (int)$this->getRequest()->get('hide_context', 0)
        );


        if ($this->getSubject()) {
            $parameters['context'] = $this->getSubject()->getContext() ? $this->getSubject()->getContext()->getId() : $defaultContext->getId();
            return $parameters;
        }

        if ($this->hasRequest()) {
            $parameters['context'] = $this->getRequest()->get('context') ?: $defaultContext->getId();

            return $parameters;
        }

        return $parameters;
    }

    public function setContextManager(ContextManagerInterface $contextManager) {
        $this->contextManager = $contextManager;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($tag)
    {
        $parameters = $this->getPersistentParameters();
        $parameters['context'] = $parameters['context']?:'default';
        $context = $this->contextManager->find($parameters['context']);
        $tag->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $tag = $this->getSubject();


        $formMapper
            ->with('Tag', array('class' => 'col-md-6'))
                ->add('name')
            ->end();

        if ($this->hasSubject() && $this->getSubject()->getId()) {
            $formMapper
                ->with('Tag', array('class' => 'col-md-6'))
                    ->add('slug')
                ->end();
        }

        $formMapper
            ->with('Tag', array('class' => 'col-md-6'))
                ->add('enabled', null, array('required' => false))
            ->end();

        if($provider = $this->getPoolProvider()) {
            if ($tag->getId()) {
                $provider->load($tag);
                $provider->buildEditForm($formMapper);
            } else {
                $provider->buildCreateForm($formMapper);
            }
        }
    }

    public function setPool(TagPool $pool) {
        $this->pool = $pool;
    }

    protected function fetchCurrentContext() {

        $context_param = $this->getPersistentParameter('context');
        $context = null;
        if($context_param) {
            $context = $this->contextManager->find($context_param);
        } else {
            $context = $this->contextManager->findOneBy(array('id'=>self::TAG_DEFAULT_CONTEXT));
        }

        if($context) {
            return $context;
        } else {
            return;
        }
    }

    protected function getPoolProvider() {
        $currentContext = $this->fetchCurrentContext();
        if ($this->pool->hasContext($currentContext->getId())) {
            $providerName = $this->pool->getProviderNameByContext($currentContext->getId());
            return $this->pool->getProvider($providerName);
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);
        $this->getPoolProvider()->postUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        parent::postPersist($object);
        $this->getPoolProvider()->postPersist($object);
    }
}
