<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
final class ContactController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_contact_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getAssociationTargetClass($atribute)];
        }


        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "contact",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_contact_index', methods: ['GET'])]
    public function apiIndex(ContactRepository $contactRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // return for api collection
        $response = $apiQueryBuilder->returnCollection($qb = $contactRepository->createQueryBuilder('contact'), $request);


        // left join
        $qb->leftJoin('contact.societe', 'societe');

        // fields
        $apiQueryBuilder->applyFields($qb, $request);
        if (!$request->query->get("fields")) {
            $qb->addSelect('societe');
        }

        // filter
        $apiQueryBuilder->applyFilters($qb, $request);

        // order
        $apiQueryBuilder->applyOrder($qb, $request);

        // count query elements
        $total = $apiQueryBuilder->getTotal($qb);
        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;

        // build links
        $links = $apiQueryBuilder->buildLinks('api_contact_index', $page, $perPage, $total, $request);

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

        $response = new JsonResponse($payload, Response::HTTP_OK, [], false);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $this->render('api/api_obj_response.html.twig', [
            'data' => $response,
        ]);
    }


            // -show
    #[Route('_api/{id}',name: 'api_contact_show', methods: ['GET'])]
    public function apiShow(Contact $contact, SerializerInterface $serializer): Response
    {
        $response = new JsonResponse($serializer->serialize($contact, 'json'), Response::HTTP_OK, [], true);
        
        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        return $this->render('api/api_obj_response.html.twig', [
            'data' => $response,
        ]);
    }


            // -new
    #[Route('_api/new', name: 'api_contact_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $response = new Response('', Response::HTTP_CREATED);
        }
        else{
            $response = new Response('', Response::HTTP_EXPECTATION_FAILED);
        }

        

        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        return $this->render('api/api_obj_response.html.twig', [
            'data' => $response,
        ]);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_contact_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $response = new Response('', Response::HTTP_ACCEPTED);
        }else{
            $response = new Response('', Response::HTTP_EXPECTATION_FAILED);
        }

        


        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        return $this->render('api/api_obj_response.html.twig', [
            'data' => $response,
        ]);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_contact_delete', methods: ['DELETE'])]
    public function apiDelete(Contact $contact, EntityManagerInterface $em): Response
    {
        $em->remove($contact);
        $em->flush();



        $response = new Response('', Response::HTTP_NO_CONTENT);
        
        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        return $this->render('api/api_obj_response.html.twig', [
            'data' => $response,
        ]);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('contact/show.html.twig', ['id' => $id]);
    }
}
