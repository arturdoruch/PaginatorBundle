<?php

namespace ArturDoruch\PaginatorBundle\Paginator\Type;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ArturDoruch\PaginatorBundle\Paginator\AbstractPaginator;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class DoctrinePaginator extends AbstractPaginator
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param Query|QueryBuilder $query A Doctrine ORM query or query builder.
     * @param bool $fetchJoinCollection
     */
    public function __construct($query, $fetchJoinCollection = true)
    {
        $this->paginator = new Paginator($query, $fetchJoinCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->paginator->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $query = $this->getQuery();
        $query->setFirstResult($this->getOffset());

        if ($limit = $this->getLimit()) {
            $query->setMaxResults($limit);
        }

        return $this->paginator->getIterator();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery()
    {
        return $this->paginator->getQuery();
    }

    /**
     * @return bool
     */
    public function getFetchJoinCollection()
    {
        return $this->paginator->getFetchJoinCollection();
    }

    /**
     * @return bool|null
     */
    public function getUseOutputWalkers()
    {
        return $this->paginator->getUseOutputWalkers();
    }

    /**
     * Sets whether the paginator will use an output walker.
     *
     * @param bool|null $useOutputWalkers
     *
     * @return $this
     */
    public function setUseOutputWalkers($useOutputWalkers)
    {
        return $this->paginator->setUseOutputWalkers($useOutputWalkers);
    }
}
 