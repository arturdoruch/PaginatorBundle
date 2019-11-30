<?php

namespace ArturDoruch\PaginatorBundle\Paginator;

use ArturDoruch\PaginatorBundle\Pagination;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
abstract class AbstractPaginator implements PaginatorInterface
{
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var \ArrayIterator
     */
    private $items;

    /**
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        if (!$this->items) {
            $this->items = $this->getItems();
        }

        return $this->items;
    }

    /**
     * @return \ArrayIterator
     */
    abstract protected function getItems();

    /**
     * @return int
     */
    protected function getLimit()
    {
        return $this->pagination->getLimit();
    }

    /**
     * @return int
     */
    protected function getOffset()
    {
        return $this->pagination->getOffset();
    }
}
