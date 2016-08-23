<?php

namespace ArturDoruch\PaginatorBundle;

use ArturDoruch\PaginatorBundle\Paginator\PaginatorInterface;
use ArturDoruch\PaginatorBundle\Paginator\Type\ArrayPaginator;
use ArturDoruch\PaginatorBundle\Paginator\Type\DoctrineMongoDBPaginator;
use ArturDoruch\PaginatorBundle\Paginator\Type\DoctrinePaginator;
use ArturDoruch\PaginatorBundle\Paginator\Type\MongoDBPaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class Paginator
{
    /**
     * @var int Default query limit
     */
    private $limit;

    public function __construct($limit)
    {
        $this->limit = (int)$limit;
    }

    /**
     * @param mixed $query A Doctrine ORM query or query builder, Doctrine mongodb ODM query or query builder,
     *                     instance of Doctrine\MongoDB\CursorInterface, instance of MongoCursor,
     *                     or array with arrays of items.
     * @param int $page
     * @param int $limit   The number of items per page. Possible values are:
     *                     -1 - fetch all items (limit will be omitted)
     *                     0 - default limit (setting in config "artur_doruch_paginator.limit") will be used
     *                     integer positive - given $limit value will be used
     * @param bool  $fetchJoinCollection Whether the query joins a collection. Uses only when $query is Doctrine query or query builder.
     *
     * @return PaginatorInterface
     */
    public function paginate($query, $page, $limit, $fetchJoinCollection = true)
    {
        $page = (int)$page;
        $limit = (int)$limit;

        if ($limit === 0) {
            $limit = $this->limit;
        }

        if ($limit == -1) {
            $page = 1;
        }

        $offset = $this->countOffset($page, $limit);

        $paginator = $this->getPaginator($query, $fetchJoinCollection);
        $totalItems = $paginator->count();

        if ($offset >= $totalItems && $totalItems > 0) {
            throw new NotFoundHttpException(sprintf('The page %d does not exist.', $page));
        }

        $pagination = new Pagination($totalItems, $page, $limit, $offset);
        $paginator->setPagination($pagination);

        return $paginator;
    }

    /**
     * Gets paginator based on $query type.
     *
     * @param mixed $query
     * @param bool  $fetchJoinCollection
     *
     * @return PaginatorInterface
     */
    private function getPaginator($query, $fetchJoinCollection)
    {
        switch (true) {
            case is_array($query):
                return new ArrayPaginator($query);

            case $query instanceof \Doctrine\ORM\Query:
            case $query instanceof \Doctrine\ORM\QueryBuilder:
                return new DoctrinePaginator($query, $fetchJoinCollection);

            case $query instanceof \Doctrine\ODM\MongoDB\Query\Builder;
            case $query instanceof \Doctrine\ODM\MongoDB\Query\Query:
            case $query instanceof \Doctrine\MongoDB\CursorInterface:
                return new DoctrineMongoDBPaginator($query);

            case $query instanceof \MongoCursor:
                return new MongoDBPaginator($query);
        }

        throw new InvalidParameterException(sprintf(
                'The $query argument must be one of types: array, Doctrine\ORM\Query, Doctrine\ORM\QueryBuilder, '.
                'Doctrine\ODM\MongoDB\Query\Builder, Doctrine\ODM\MongoDB\Query\Query, Doctrine\MongoDB\CursorInterface, '.
                'MongoCursor, but given type of "%s".',
                is_object($query) ? get_class($query) : gettype($query)
            ));
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return int
     */
    private function countOffset($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        return ($offset < 0) ? 0 : $offset;
    }
}
