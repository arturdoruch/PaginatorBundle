<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator\Source;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ArturDoruch\PaginatorBundle\Paginator\PaginatorAware;


class DoctrineQueryPaginator extends PaginatorAware
{

    private $doctrinePaginator;

    /**
     * {@inheritdoc}
     */
    public function __construct($query, $fetchJoinCollection = true)
    {
        $this->doctrinePaginator = new DoctrinePaginator($query, $fetchJoinCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->doctrinePaginator->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->doctrinePaginator->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->doctrinePaginator->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getFetchJoinCollection()
    {
        return $this->doctrinePaginator->getFetchJoinCollection();
    }

    public function getUseOutputWalkers()
    {
        return $this->doctrinePaginator->getUseOutputWalkers();
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
        return $this->doctrinePaginator->setUseOutputWalkers($useOutputWalkers);
    }

}
 