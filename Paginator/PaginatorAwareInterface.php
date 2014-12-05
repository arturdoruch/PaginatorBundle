<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator;

use ArturDoruch\PaginatorBundle\Pagination;


interface PaginatorAwareInterface
{
    /**
     * Sets pagination
     *
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination);

    /**
     * @return Pagination
     */
    public function getPagination();
}
 