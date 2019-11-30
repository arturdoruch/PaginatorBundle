<?php

namespace ArturDoruch\PaginatorBundle\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
     * @var \ArrayIterator
     */
    private $iterator;

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
    protected function getItems()
    {
        if (!$this->iterator) {
            $query = $this->getQuery();
            $query->setFirstResult($this->getOffset());

            if ($limit = $this->getLimit()) {
                $query->setMaxResults($limit);
            }

            $this->iterator = $this->paginator->getIterator();
        }

        return $this->iterator;
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
     */
    public function setUseOutputWalkers($useOutputWalkers)
    {
        $this->paginator->setUseOutputWalkers($useOutputWalkers);
    }
}
 