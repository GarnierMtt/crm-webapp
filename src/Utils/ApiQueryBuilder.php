<?php

namespace App\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class ApiQueryBuilder extends AbstractController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private NormalizerInterface $normalizer,
        private EntityManagerInterface $em,
    ) {}

    /**
     * OLD function for INDEX ####
     */
    public function returnIndexOld(QueryBuilder $qb, Request $request, $entity): Response
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
            $total = $queryClone->select('COUNT(' . $entity . '.id)')
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

            // return JSON
            return $this
                ->apiReturn(new JsonResponse(
                    $payload, 
                    Response::HTTP_OK, 
                    [], 
                    false)
                ->setEncodingOptions(
                    JSON_UNESCAPED_UNICODE | 
                    JSON_UNESCAPED_SLASHES)
                );
    }

    
    /**
     * function for INDEX
     */
    public function returnIndex($repository, Request $request, $className, $ignored = []): Response
    {
        $query = [];

        //!\\ filtering not implemented, no out the box solution with serializer
        //criteria
            $query[0] = [];

        // orderBy
            $query[1] = [];
            if ($order = $request->query->getString("order")) {
                $orders = explode(',', $order);
                foreach ($orders as $o) {
                    if($o[0] === '!'){
                        $query[1][substr($o, 1)] = 'DESC';
                    }else{
                        $query[1][$o] = 'ASC';
                    }
                }
            }

        // pagination
            $page = max(1, $request->query->getInt("page"));
            $perPage = max(0, $request->query->getInt("per_page"));
            if (!$perPage) {
                $page = 1;
                $perPage = null;
            }
            //limit
            $query[2] = $perPage;
            //offset
            $query[3] = ($page - 1) * $perPage;

        // fetch data
            $data = $repository->findBy($query[0],$query[1],$query[2],$query[3]);

        // count elements for meta
            $total = count($data);
            $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;

        // get url without filters
            $baseUrl = $this->urlGenerator->generate('api_' . $className . '_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // Build links manually without encoding
            if($queryParams = $request->query->all()){
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
            }else{
                $links['self'] = $baseUrl;
            }
            
        // set context (sending fields or not)

            $context = $this->fieldSelector($request->query->getString("fields"), $ignored);

        // set paylod and normalize
            $payload = [
                'data' => $this->normalizer->normalize($data, 'object', $context),
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'links' => $links,
                ],
            ];

        // return JSON
            return $this
                ->apiReturn(new JsonResponse(
                    $payload, 
                    Response::HTTP_OK, 
                    [], 
                    false)
                ->setEncodingOptions(
                    JSON_UNESCAPED_UNICODE | 
                    JSON_UNESCAPED_SLASHES)
                );
    }



    /**
     * function for SHOW
     */
    public function returnShow($entity, Request $request, $ignored = []): Response
    {
        // set context (sending fields or not)

            $context = $this->fieldSelector($request->query->getString("fields"), $ignored);
            
        // set paylod and normalize
            $payload = ['data' => $this->normalizer->normalize($entity, 'object', $context)];

        // return JSON
            return $this
                ->apiReturn(new JsonResponse(
                    $payload, 
                    Response::HTTP_OK, 
                    [], 
                    false)
                ->setEncodingOptions(
                    JSON_UNESCAPED_UNICODE | 
                    JSON_UNESCAPED_SLASHES)
                );
    }


    /**
     * function for NEW
     */
    public function returnNew($entity, $form): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return $this
                ->apiReturn(new JsonResponse(
                    '', 
                    Response::HTTP_CREATED, 
                    [], 
                    false)
                );
        }

        return $this
            ->apiReturn(new JsonResponse(
                '', 
                Response::HTTP_EXPECTATION_FAILED, 
                [], 
                false)
            );
    }


    /**
     * function for EDIT
     */
    public function returnEdit($form): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            
            return $this
                ->apiReturn(new JsonResponse(
                    '', 
                    Response::HTTP_ACCEPTED, 
                    [], 
                    false)
                );
        }
        
        return $this
            ->apiReturn(new JsonResponse(
                '', 
                Response::HTTP_EXPECTATION_FAILED, 
                [], 
                false)
            );
    }


    /**
     * function for DELETE
     */
    public function returnDelete($entity): Response
    {
        $this->em->remove($entity);
        $this->em->flush();

        return $this
            ->apiReturn(new JsonResponse(
                '', 
                Response::HTTP_NO_CONTENT, 
                [], 
                false)
            );
    }




     /**
     * Apply field selection to query builder
     */
    private function fieldSelector($fields, $ignored): array
    {
        if($fields) {
            $selected = [];
            foreach (explode(',', $fields) as $f) {
                if($f[0] === '!'){
                    $ignored[] = substr($f, 1);
                }else{
                    $selected[] = $f;
                }
            }
        }elseif(!$ignored){
            return [];
        }
            if($selected){
                $context[AbstractNormalizer::ATTRIBUTES] = $selected;
            }
            if($ignored){
                $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = $ignored;
            }
        return $context;
    }


    /**
     * reconstruct URL query
     */
    private function buildUnencodedQuery(array $params): string 
    {
        $queryParts = [];
        foreach ($params as $key => $value) {
            $queryParts[] = $key . '=' . $value;
        }
        return implode('&', $queryParts);
    }


    /**
     * api return
     */
    public function apiReturn($response): Response
    {
        // response
        if($_SERVER["HTTP_ACCEPT"] == "text/html"){
            $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
            return $this->render('api/api_obj_response.html.twig', [
                'data' => $response,
            ]);
        }
        return $response;
    }
}