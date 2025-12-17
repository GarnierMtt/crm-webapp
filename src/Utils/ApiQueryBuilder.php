<?php

namespace App\Utils;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiQueryBuilder
{
    private ?int $page = 1;
    private ?int $perPage = null;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * function for array colection
     */
    public function returnCollection(QueryBuilder $qb, Request $request): Response
    {
        $this->applyPagination($qb, $request);

        $payload = [];

        return new JsonResponse($payload, Response::HTTP_OK, [], false)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }


    /**
     * Apply pagination parameters to query builder
     */
    private function applyPagination(QueryBuilder $qb, Request $request)
    {
        $page = max(1, $request->query->get("page"));
        $perPage = max(0, $request->query->get("per_page"));
        if (!$perPage) {
            $page = 1;
            $perPage = null;
        }

        $this->page = $page;
        $this->perPage = $perPage;

        $qb->setFirstResult(($page - 1) * $perPage)
           ->setMaxResults($perPage);
    }

    /**
     * Apply field selection to query builder
     */
    public function applyFields(QueryBuilder $qb, Request $request): void
    {
        if ($fields = $request->query->get("fields")) {
            $selects = [];
            $fieldList = explode(',', $fields);
            foreach ($fieldList as $f) {
                $f = trim($f);
                if (strpos($f, '.')) {
                    list($assoc, $subfield) = explode('.', $f, 2);
                    $selects[] = "$assoc.$subfield as {$assoc}_{$subfield}";
                } else {
                    $selects[] = "contact.$f";
                }
            }
            $qb->select(implode(', ', $selects));
        }
    }

    /**
     * Apply filters to query builder
     */
    public function applyFilters(QueryBuilder $qb, Request $request): void
    {
        $filter = $request->query->get("filter");
        if (!$filter) {
            return;
        }

        foreach (explode(',', $filter) as $part) {
            $operators = ['!:', '>:', '<:', '!~', ':', '>', '<', '~'];
            $op = null;
            $pos = -1;

            foreach ($operators as $o) {
                $p = strpos($part, $o);
                if ($p !== false) {
                    $pos = $p;
                    $op = $o;
                    break;
                }
            }

            if (!$op) {
                continue;
            }

            $field = substr($part, 0, $pos);
            $value = substr($part, $pos + strlen($op));
            $param = 'param_' . $field . '_' . str_replace([':', '!', '~', '>', '<'], '', $op);
            $alias = 'contact';

            if ($field === 'societe') {
                $alias = $field;
                $field = 'id';
                $value = (int) $value;
            }

            switch ($op) {
                case ':':
                    $qb->andWhere("$alias.$field = :$param");
                    break;
                case '!:':
                    $qb->andWhere("$alias.$field != :$param");
                    break;
                case '>':
                    $qb->andWhere("$alias.$field > :$param");
                    break;
                case '<':
                    $qb->andWhere("$alias.$field < :$param");
                    break;
                case '>:':
                    $qb->andWhere("$alias.$field >= :$param");
                    break;
                case '<:':
                    $qb->andWhere("$alias.$field <= :$param");
                    break;
                case '~':
                    $qb->andWhere("$alias.$field LIKE :$param");
                    $value = '%' . $value . '%';
                    break;
                case '!~':
                    $qb->andWhere("$alias.$field NOT LIKE :$param");
                    $value = '%' . $value . '%';
                    break;
            }

            $qb->setParameter($param, $value);
        }
    }

    /**
     * Apply ordering to query builder
     */
    public function applyOrder(QueryBuilder $qb, Request $request): void
    {
        $order = $request->query->get("order");
        if (!$order) {
            return;
        }

        $orders = explode(',', $order);
        foreach ($orders as $o) {
            $parts = explode(':', $o, 2);
            $field = trim($parts[0]);
            $dir = isset($parts[1]) ? strtoupper(trim($parts[1])) : 'ASC';
            if (!in_array($dir, ['ASC', 'DESC'])) {
                $dir = 'ASC';
            }

            if (strpos($field, '.')) {
                list($assoc, $subfield) = explode('.', $field, 2);
                $qb->addOrderBy("$assoc.$subfield", $dir);
            } else {
                $qb->addOrderBy("contact.$field", $dir);
            }
        }
    }

    /**
     * Get total count from query builder
     */
    public function getTotal(QueryBuilder $qb): int
    {
        $countQb = clone $qb;
        $countQb->setFirstResult(null)
                ->setMaxResults(null);
        return (int) $countQb->select('COUNT(contact.id)')
                             ->resetDQLPart('orderBy')
                             ->getQuery()
                             ->getSingleScalarResult();
    }

    /**
     * Build pagination links
     */
    public function buildLinks(
        string $routeName,
        int $page,
        int $perPage,
        int $total,
        Request $request
    ): array {
        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
        $queryParams = $request->query->all();
        
        $buildUnencodedQuery = function (array $params): string {
            $queryParts = [];
            foreach ($params as $key => $value) {
                $queryParts[] = $key . '=' . $value;
            }
            return implode('&', $queryParts);
        };

        $baseUrl = $this->urlGenerator->generate($routeName, [], UrlGeneratorInterface::ABSOLUTE_URL);
        $baseUrl = rtrim($baseUrl, '?');

        $links = [];
        if ($page > 2) {
            $links['first'] = $baseUrl . '?' . $buildUnencodedQuery(array_merge($queryParams, ['page' => 1]));
        }
        if ($page > 1) {
            $links['prev'] = $baseUrl . '?' . $buildUnencodedQuery(array_merge($queryParams, ['page' => $page - 1]));
        }
        $links['self'] = $baseUrl . '?' . $buildUnencodedQuery($queryParams);
        if ($page < $totalPages) {
            $links['next'] = $baseUrl . '?' . $buildUnencodedQuery(array_merge($queryParams, ['page' => $page + 1]));
        }
        if ($page + 1 < $totalPages) {
            $links['last'] = $baseUrl . '?' . $buildUnencodedQuery(array_merge($queryParams, ['page' => $totalPages]));
        }

        return $links;
    }
}