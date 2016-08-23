<?php

namespace ArturDoruch\PaginatorBundle\Paginator\Type;

use ArturDoruch\PaginatorBundle\Paginator\AbstractPaginator;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class ArrayPaginator extends AbstractPaginator
{
    /**
     * @var array Items
     */
    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $limit = $this->getLimit() ?: $this->count();
        $items = array_splice($this->array, $this->getOffset(), $limit);

        return new \ArrayIterator($items);
    }
}
 