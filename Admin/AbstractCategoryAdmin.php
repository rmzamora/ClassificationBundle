<?php

namespace Rz\ClassificationBundle\Admin;

use Sonata\ClassificationBundle\Admin\CategoryAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Entity\ContextManager;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;

abstract class AbstractCategoryAdmin extends Admin
{
    protected $pool;
    protected $defaultContext;
    protected $contextManager;
    protected $slugify;
    protected $settings;
    protected $provider;

    /**
     * @param string                  $code
     * @param string                  $class
     * @param string                  $baseControllerName
     * @param ContextManagerInterface $contextManager
     */
    public function __construct($code, $class, $baseControllerName, ContextManagerInterface $contextManager)
    {
        parent::__construct($code, $class, $baseControllerName, $contextManager);
        $this->settings = [];
    }


    /**
     * @return mixed
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @param mixed $pool
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
    }

    /**
     * @return mixed
     */
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }

    /**
     * @return mixed
     */
    public function getContextManager()
    {
        return $this->contextManager;
    }

    /**
     * @param mixed $contextManager
     */
    public function setContextManager($contextManager)
    {
        $this->contextManager = $contextManager;
    }

    /**
     * @param mixed $defaultContext
     */
    public function setDefaultContext($defaultContext)
    {
        $this->defaultContext = $defaultContext;
    }

    /**
     * @return mixed
     */
    public function getSlugify()
    {
        return $this->slugify;
    }

    /**
     * @param mixed $slugify
     */
    public function setSlugify($slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param mixed $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritDoc}
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function hasProvider($interface = null)
    {
        if (!$interface) {
            return isset($this->provider);
        }

        if ($this->provider instanceof $interface) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }
}
