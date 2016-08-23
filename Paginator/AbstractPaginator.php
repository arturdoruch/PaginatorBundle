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
 