<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use App\Form\DeleteForm;
use App\Repository\ContactRepository;
use App\Repository\SocieteRepository;
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
    public function apiNew(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
        }

        $jsonContact = $serializer->serialize($contact, 'json');


        return new JsonResponse($jsonContact, Response::HTTP_CREATED, [], true);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_contact_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contact $contact, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        $jsonContact = $serializer->serialize($contact, 'json');



        return new JsonResponse($jsonContact, Response::HTTP_ACCEPTED, [], true);
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

            // -new
    #[Route('/new', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SocieteRepository $societeRepository,EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);
        $path = $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);


        // Set Societe if provided
        $societeId = $request->query->get('societe');
        if ($societeId) {
            $path = $this->redirectToRoute('app_societe_show', ['id' => $societeId], Response::HTTP_SEE_OTHER);
            $societe = $societeRepository->find($societeId);
            if ($societe) {
                $contact->setSociete($societe);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $path;
        }



        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'formContact' => $form,
        ]);
    }

            // -show
    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('contact/show.html.twig', ['id' => $id]);
    }

            // -edit
    #[Route('/{id}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'formContact' => $form,
        ]);
    }
}
