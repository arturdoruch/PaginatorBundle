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
     * @var int Default query limit
     */
    private $limit;

    public function __construct($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * @param Query|QueryBuilder $query A Doctrine ORM query or query builder.
     * @param int $page
     * @param number|null $limit If -1 then will be fetch all items without limit.
     *                           If $limit is empty (null or "") then will be used default limit value.
     *                           Finally when $limit is positive value then will be used.
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

        if ($limit == -1) {
            // Without limit
            $page = 1;
            $offset = 0;
        } else {
            $page = (int) $page;
            $limit = (int) $limit;

            if (empty($limit)) {
                $limit = $this->limit;
            }

            $offset = $this->getOffset($page, $limit);

            $query->setFirstResult($offset);
            if ($limit >= 0) {
                $query->setMaxResults($limit);
            }
        }

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
        $offset = ($page - 1) * $limit;

        return $offset > 0 ? $offset : 0;
    }
}
