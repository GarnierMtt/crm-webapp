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

    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_contact_index', methods: ['GET'])]
    public function apiIndex(ContactRepository $contactRepository, SerializerInterface $serializer): JsonResponse
    {
        $contacts = $contactRepository->findAll();
        $jsonContacts = $serializer->serialize($contacts, 'json');



        return new JsonResponse($jsonContacts, Response::HTTP_OK, [], true);
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
