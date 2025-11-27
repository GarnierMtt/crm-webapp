<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact')]
final class ContactController extends AbstractController
{
    //// api documentation
    public function documentation($data, $em): Response
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



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "contact",
            'atributes' => $mappings,
            'data' => $data,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_contact_index', methods: ['GET'])]
    public function apiIndex(ContactRepository $contactRepository, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $response = new JsonResponse($serializer->serialize($contactRepository->findAll(), 'json'), Response::HTTP_OK, [], true);
        
        if($_SERVER["HTTP_ACCEPT"] == "application/json"){
            return $response;
        }

        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        return $this->documentation($response, $em);
    }

            // -show
    #[Route('_api/{id}',name: 'api_contact_show', methods: ['GET'])]
    public function apiShow(Contact $contact, SerializerInterface $serializer): JsonResponse
    {
        $jsonContact = $serializer->serialize($contact, 'json');



        return new JsonResponse($jsonContact, Response::HTTP_OK, [], true);
    }

            // -new
    #[Route('_api/new', name: 'api_contact_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return new Response('', Response::HTTP_CREATED);
        }


        
        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_contact_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return new Response('', Response::HTTP_ACCEPTED);
        }



        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -delete
    #[Route('_api/{id}', name: 'api_contact_delete', methods: ['DELETE'])]
    public function apiDelete(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();



        return new Response('', Response::HTTP_NO_CONTENT);
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
