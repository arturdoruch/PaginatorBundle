<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Paginator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ArturDoruch\PaginatorBundle\Pagination;


class Paginator
{
    /**
     * @param Query|QueryBuilder $query A Doctrine ORM query or query builder.
     * @param int $page
     * @param int $limit
     * @param bool $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return ExtendedDoctrinePaginator
     */
    public function paginate($query, $page, $limit, $fetchJoinCollection = true)
    {
        if (!$query instanceof QueryBuilder && !$query instanceof Query) {
            throw new InvalidParameterException(
                sprintf('Parameter "query" must be instance of Doctrine\ORM\Query or Doctrine\ORM\QueryBuilder')
            );
        }

        $offset = $this->getOffset($page, $limit);

        if ($limit < 1) {
            $limit = 999999;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $query
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new ExtendedDoctrinePaginator($query, $fetchJoinCollection);
        $totalItems = $paginator->count();

        if ($offset >= $totalItems && $totalItems > 0) {
            throw new NotFoundHttpException(sprintf('Page "%d" does not exist.', $page));
        }

        $pagination = new Pagination($totalItems, $page, $limit, $offset);
        $paginator->setPagination($pagination);

        return $paginator;
    }

    private function getOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }
}
