<?php

namespace Rz\ClassificationBundle\Provider;

use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\CoreBundle\Validator\ErrorElement;

class CategoryPool extends Pool
{
    /**
     * @param string                 $name
     * @param CategoryProviderInterface $instance
     *
     * @return void
     */
    public function addProvider($name, CategoryProviderInterface $instance)
    {
        $this->providers[$name] = $instance;
    }

    /**
     * @param \Sonata\CoreBundle\Validator\ErrorElement $errorElement
     * @param \Sonata\ClassificationBundle\Model\CategoryInterface   $category
     *
     * @return void
     */
    public function validate(ErrorElement $errorElement, CategoryInterface $category)
    {

    }
}
