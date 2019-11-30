<?php

namespace ArturDoruch\PaginatorBundle\Paginator;

use ArturDoruch\PaginatorBundle\Pagination;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
interface PaginatorInterface extends \Countable, \IteratorAggregate
{
    /**
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination);

    /**
     * @return Pagination
     */
    public function getPagination();
}
 