<?php

namespace ArturDoruch\PaginatorBundle\Paginator\Type;

use ArturDoruch\PaginatorBundle\Paginator\AbstractPaginator;
use Doctrine\MongoDB\CursorInterface;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class DoctrineMongoDBPaginator extends AbstractPaginator
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var CursorInterface
     */
    private $cursor;

    /**
     * @param Builder|Query|CursorInterface $query
     */
    public function __construct($query)
    {
        if ($query instanceof CursorInterface) {
            $this->cursor = $query;

            return;
        }

        if ($query instanceof Builder) {
            $query = $query->getQuery();
        }

        if ($query->getType() !== Query::TYPE_FIND) {
            throw new \UnexpectedValueException('ODM query must be a FIND type query.');
        }

        $this->query = $query;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCursor()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $cursor = $this->getCursor();
        $cursor->skip($this->getOffset());

        if ($limit = $this->getLimit()) {
            $cursor->limit($limit);
        }

        return new \ArrayIterator($cursor->toArray());
    }

    /**
     * @return CursorInterface
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    private function getCursor()
    {
        if (!$this->cursor) {
            $this->cursor = clone $this->query->execute();
        }

        return $this->cursor;
    }
}
 