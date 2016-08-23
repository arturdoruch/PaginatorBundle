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
     * Gets number of total items
     *
     * @return int
     */
    public function totalItems()
    {
        return $this->totalItems;
    }

    /**
     * Gets number of total pages
     *
     * @return int
     */
    public function totalPages()
    {
        if ($this->limit < 1) {
            return 1;
        }

        return (int)ceil($this->totalItems / $this->limit);
    }

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
    public function previousPage()
    {
        return $this->page - 1;
    }

    /**
     * @return int
     */
    public function nextPage()
    {
        return $this->page + 1;
    }

    /**
     * @return bool
     */
    public function hasPreviousPage()
    {
        return $this->previousPage() >= 1;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->nextPage() <= $this->totalPages();
    }
}
 