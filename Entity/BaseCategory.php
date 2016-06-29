<?php

namespace Rz\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCategory as Category;

abstract class BaseCategory extends Category
{
    protected $settings;

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
     * {@inheritdoc}
     */
    public function __toString()
    {
        $description = $this->getParent() ? $this->getParent()->getName().' - ' : null;
        $description .= $this->getName() ?: 'n/a';
        return $description;
    }
}
