<?php

namespace ArturDoruch\PaginatorBundle;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class Pagination
{
    /**
     * @var int
     */
    private $totalItems;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    public function __construct($totalItems, $page = 1, $limit = 10, $offset = 0)
    {
        $this->totalItems = (int)$totalItems;
        $this->limit = (int)$limit;
        $this->page = (int)$page;
        $this->offset = (int)$offset;

        if ($this->limit < 1) {
            $this->limit = 0;
        }
        if ($this->page < 1) {
            $this->page = 1;
        }
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        if ($this->limit < 1) {
            return 1;
        }

        return (int)ceil($this->totalItems / $this->limit);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int The positive integer or 0 if no limit.
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->page - 1;
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return $this->page + 1;
    }

    /**
     * @return bool
     */
    public function hasPreviousPage()
    {
        return $this->getPreviousPage() >= 1;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->getNextPage() <= $this->getTotalPages();
    }
}
 