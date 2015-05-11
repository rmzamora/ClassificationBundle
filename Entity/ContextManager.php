<?php



namespace Rz\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\ContextManager as BaseManager;
use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\CoreBundle\Model\BaseEntityManager;

use Sonata\DatagridBundle\Pager\Doctrine\pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;

class ContextManager extends BaseManager
{
    /**
     * {@inheritdoc}
     */
    public function findAllExcept($parameters)
    {
        $queryBuilder = $this->em->getRepository($this->class)->createQueryBuilder('c');
        $query = $queryBuilder
            ->select('c')
            ->where('c.id != :id')
            ->getQuery()
            ->useResultCache(true, 3600);

        $query->setParameters($parameters);

        return $query->getResult();
    }
}
