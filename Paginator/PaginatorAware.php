<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator;

use ArturDoruch\PaginatorBundle\Pagination;


abstract class PaginatorAware implements PaginatorAwareInterface
{
    /**
     * @var Pagination
     */
    protected $pagination;

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
}
 