<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator;

use ArturDoruch\PaginatorBundle\Pagination;


class ArrayPaginator implements \Countable, \IteratorAggregate, PaginatorInterface
{
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var array Items
     */
    private $array;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $array, $offset, $limit)
    {
        $this->array = $array;
        $this->offset = (int) $offset;
        $this->limit = (int) $limit;
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

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $items = array_splice($this->array, $this->offset, $this->limit);

        return new \ArrayIterator($items);
    }

    /**
     * Gets total items count
     *
     * @return int;
     */
    public function count()
    {
        return count($this->array);
    }
}
 