<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ArturDoruch\PaginatorBundle\Pagination;


class ExtendedDoctrinePaginator extends DoctrinePaginator
{
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * {@inheritdoc}
     */
    public function __construct($query, $fetchJoinCollection = true)
    {
        parent::__construct($query, $fetchJoinCollection);
    }

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
 