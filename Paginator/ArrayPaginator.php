<?php

namespace ArturDoruch\PaginatorBundle\Paginator;

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
    protected function getItems()
    {
        $limit = $this->getLimit() ?: $this->count();
        $items = array_slice($this->array, $this->getOffset(), $limit);

        return new \ArrayIterator($items);
    }
}
 