<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator\Source;

use ArturDoruch\PaginatorBundle\Paginator\PaginatorAware;


class ArrayPaginator extends PaginatorAware implements \Countable, \IteratorAggregate
{
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
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        if ($this->limit < 0) {
            $this->limit = $this->count();
        }

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
 