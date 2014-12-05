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
use ArturDoruch\PaginatorBundle\Paginator\Source\ArrayPaginator;
use ArturDoruch\PaginatorBundle\Paginator\Source\DoctrineQueryPaginator;


class Paginator
{
    /**
     * @var int Default query limit
     */
    private $limit;

    /**
     * @var int
     */
    private $offset = 0;

    public function __construct($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * @param Query|QueryBuilder|array $query A Doctrine ORM query or query builder or array with items array.
     * @param int $page
     * @param number|null $limit If -1 then will be fetch all items without limit.
     *                           If $limit is empty (null or "") then will be used default limit value.
     *                           Finally when $limit is positive value then will be used.
     * @param bool $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return ArrayPaginator|DoctrineQueryPaginator
     */
    public function paginate($query, $page, $limit, $fetchJoinCollection = true)
    {
        if ($limit == -1) {
            // Without limit
            $this->limit = -1;
            $page = 1;
        } else {
            $page = (int) $page;
            $limit = (int) $limit;

            if (!empty($limit)) {
                $this->limit = $limit;
            }
        }

        $this->setOffset($page, $this->limit);

        $paginator = $this->getPaginator($query, $fetchJoinCollection);
        $totalItems = $paginator->count();

        if ($this->offset >= $totalItems && $totalItems > 0) {
            throw new NotFoundHttpException(sprintf('Page "%d" does not exist.', $page));
        }

        $pagination = new Pagination($totalItems, $page, $this->limit, $this->offset);
        $paginator->setPagination($pagination);

        return $paginator;
    }

    /**
     * Gets paginator based on $query type
     *
     * @param Query|QueryBuilder|array $query A Doctrine ORM query or query builder or array with items array.
     * @param bool $fetchJoinCollection
     *
     * @return ArrayPaginator|DoctrineQueryPaginator
     */
    private function getPaginator($query, $fetchJoinCollection = true)
    {
        if (!is_array($query) && !$query instanceof QueryBuilder && !$query instanceof Query) {
            throw new InvalidParameterException(
                sprintf('Parameter "query" must be array or instance of Doctrine\ORM\Query or Doctrine\ORM\QueryBuilder.')
            );
        }

        if (is_array($query)) {
            return new ArrayPaginator($query, $this->offset, $this->limit);
        } else {
            $query->setFirstResult($this->offset);
            if ($this->limit >= 0) {
                $query->setMaxResults($this->limit);
            }

            return new DoctrineQueryPaginator($query, $fetchJoinCollection);
        }
    }


    private function setOffset($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $this->offset = ($offset < 0) ? 0 : $offset;
    }
}
