<?php

namespace App\Utils;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiQueryBuilder
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * function for array colection
     */
    public function returnCollection(QueryBuilder $qb, Request $request, $entity): JsonResponse
    {
        // pagination
        $page = max(1, $request->query->get("page"));
        $perPage = max(0, $request->query->get("per_page"));
        if (!$perPage) {
            $page = 1;
            $perPage = null;
        }
        $qb->setFirstResult(($page - 1) * $perPage)
           ->setMaxResults($perPage);

        // field selection
        $this->advancedQuery($qb, $request, $entity);

        // filters
        if ($filter = $request->query->get("filter")) {
            foreach (explode(',', $filter) as $key=>$part) {
                $operators = [
                    '>=' => '>:', // greater or equal
                    '>' => '>', // greater
                    '<=' => '<:', // lesser or equal
                    '<' => '<', // lesser
                    '!=' => '!:', // not equal
                    '=' => ':', // equal
                    'NOT LIKE' => '!~', // dose not containe
                    'LIKE' => '~', // dose containe
                ];

                // findes operator and it's position
                foreach ($operators as $o) {
                    $p = strpos($part, $o);
                    if ($p) {
                        $pos = $p;
                        $op = $o;
                        break;
                    }
                }

                // skips if no operator is found
                if ($op) {
                    // seperates element and value
                    $field = substr($part, 0, $pos);
                    $value = substr($part, $pos + strlen($op));
                    // add whildcards for 'LIKE' filters
                    if (strpos($op, '~')){
                        $value = '%' . $value . '%';
                    }
                    // handles joined fields and set enty alias
                    if (strpos($field, '.')) {
                        list($alias, $field) = explode('.', $field, 2);
                    } else {
                        $alias = $entity;
                    }

                    // creates queary parameter
                    $qb->andWhere("$alias.$field " . array_search($op, $operators) . " ?$key");
                    // sets value
                    $qb->setParameter($key, $value);  
                }
            }
        }

        // ordering 
        if ($order = $request->query->get("order")) {
            $orders = explode(',', $order);
            foreach ($orders as $o) {
                list($field, $dir) = explode(':', $o, 2);
                if (!in_array($dir, ['ASC', 'DESC'])) {
                    $dir = 'ASC';
                }

                if (strpos($field, '.')) {
                    list($assoc, $subfield) = explode('.', $field, 2);
                    $qb->addOrderBy("$assoc.$subfield", $dir);
                } else {
                    $qb->addOrderBy("$entity.$field", $dir);
                }
            }
        }

        // element count
        $queryClone = clone $qb;
        $queryClone->setFirstResult(null)
                   ->setMaxResults(null);
        $total = $queryClone->select('COUNT(contact.id)')
                            ->resetDQLPart('orderBy')
                            ->getQuery()
                            ->getSingleScalarResult();

        // meta data
        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
        // get url without filters
        $baseUrl = $this->urlGenerator->generate('api_' . $entity . '_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // Build links manually without encoding
        $queryParams = $request->query->all();
        $links = [];
        if ($page > 2) {
            $links['first'] = $baseUrl . '?' . $this->buildUnencodedQuery(array_merge($queryParams, ['page' => 1]));
        }
        if ($page > 1) {
            $links['prev'] = $baseUrl . '?' . $this->buildUnencodedQuery(array_merge($queryParams, ['page' => $page - 1]));
        }
        $links['self'] = $baseUrl . '?' . $this->buildUnencodedQuery($queryParams);
        if ($page < $totalPages) {
            $links['next'] = $baseUrl . '?' . $this->buildUnencodedQuery(array_merge($queryParams, ['page' => $page + 1]));
        }
        if ($page + 1 < $totalPages) {
            $links['last'] = $baseUrl . '?' . $this->buildUnencodedQuery(array_merge($queryParams, ['page' => $totalPages]));
        }


        $payload = [
            'data' => $qb->getQuery()->getArrayResult(),
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'links' => $links,
            ],
        ];

        return new JsonResponse($payload, Response::HTTP_OK, [], false)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }


    /**
     * Apply field selection to query builder
     */
    public function advancedQuery(QueryBuilder $qb, Request $request, $entity): void
    {
        if ($fields = $request->query->get("fields")) {
            $selects = [];
            foreach (explode(',', $fields) as $f) {
                if (strpos($f, '.')) {
                    $g = str_replace(".", "}_{", $f);
                    $selects[] = "$f as {$g}";
                } else {
                    $selects[] = "$entity.$f";
                }
            }
            $qb->select(implode(', ', $selects));
        }
    }

    private function buildUnencodedQuery(array $params): string 
    {
        $queryParts = [];
        foreach ($params as $key => $value) {
            $queryParts[] = $key . '=' . $value;
        }
        return implode('&', $queryParts);
    }
}