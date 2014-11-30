<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle;


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
        $this->totalItems = (int) $totalItems;
        $this->limit = (int) $limit;
        $this->page = (int) $page;
        $this->offset = (int) $offset;

        if ($this->limit < 1) {
            $this->limit = 999999;
        }
        if ($this->page < 1) {
            $this->page = 1;
        }
    }

    /**
     * @return int Total items count
     */
    public function totalItems()
    {
        return $this->totalItems;
    }

    /**
     * @return int
     */
    public function totalPages()
    {
        return (int) ceil($this->totalItems / $this->limit);
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getLimit()
    {
        return $this->limit;
    }

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
 