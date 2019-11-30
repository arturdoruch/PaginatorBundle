<?php

namespace ArturDoruch\PaginatorBundle\Paginator;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class MongoDBPaginator extends AbstractPaginator
{
    /**
     * @var \MongoCursor
     */
    private $cursor;

    public function __construct(\MongoCursor $cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->cursor->count();
    }

    /**
     * @return \ArrayIterator
     */
    protected function getItems()
    {
        $this->cursor->skip($this->getOffset());

        if ($limit = $this->getLimit()) {
            $this->cursor->limit($limit);
        }

        return new \ArrayIterator(iterator_to_array($this->cursor));
    }
}
 