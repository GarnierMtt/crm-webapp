<?php

namespace App\Controller;

use App\Entity\RelationContactAdresse;
use App\Form\RelationContactAdresseForm;
use App\Repository\RelationContactAdresseRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relation/contact/adresse')]
final class RelationContactAdresseController extends AbstractController
{

    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_relation_contact_adresse_index', methods: ['GET'])]
    public function apiIndex(RelationContactAdresseRepository $relationContactAdresseRepository, SerializerInterface $serializer): JsonResponse
    {
        $relationsContactAdresse = $relationContactAdresseRepository->findAll();
        $jsonRelationsContactAdresse = $serializer->serialize($relationsContactAdresse, 'json');



        return new JsonResponse($jsonRelationsContactAdresse, Response::HTTP_OK, [], true);
    }

            // -show
    #[Route('_api/{id}', name: 'api_relation_contact_adresse_show', methods: ['GET'])]
    public function apiShow(RelationContactAdresse $relationContactAdresse, SerializerInterface $serializer): JsonResponse
    {
        $jsonRelationContactAdresse = $serializer->serialize($relationContactAdresse, 'json');



        return new JsonResponse($jsonRelationContactAdresse, Response::HTTP_OK, [], true);
    }

            // -new
    #[Route('_api/new', name: 'api_relation_contact_adresse_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $relationContactAdresse = new RelationContactAdresse();
        $form = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relationContactAdresse);
            $entityManager->flush();
            
            return new Response('', Response::HTTP_CREATED);
        }


        
        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_relation_contact_adresse_edit', methods: ['POST'])]
    public function apiEdit(Request $request, RelationContactAdresse $relationContactAdresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return new Response('', Response::HTTP_ACCEPTED);
        }



        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -delete
    #[Route('_api/{id}', name: 'api_relation_contact_adresse_delete', methods: ['DELETE'])]
    public function apiDelete(RelationContactAdresse $relationContactAdresse, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($relationContactAdresse);
        $entityManager->flush();



        return new Response('', Response::HTTP_NO_CONTENT);
    }




    //// routes vues
    #[Route(name: 'app_relation_contact_adresse_index', methods: ['GET'])]
    public function index()
    {
    }
    
    #[Route('/new', name: 'app_relation_contact_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $relationContactAdresse = new RelationContactAdresse();
        $form = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);
        $form->handleRequest($request);
        $path = $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);


        // Set contact if provided
        $contactId = $request->query->get('contact');
        if ($contactId) {
            $path = $this->redirectToRoute('app_contact_show', ['id' => $contactId], Response::HTTP_SEE_OTHER);
            $contact = $contactRepository->find($contactId);
            if ($contact) {
                $relationContactAdresse->setContact($contact);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relationContactAdresse);
            $entityManager->flush();

            return $path;
        }

        return $this->render('relation_contact_adresse/new.html.twig', [
            'relation_contact_adresse' => $relationContactAdresse,
            'formRelContactAdresse' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relation_contact_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RelationContactAdresse $relationContactAdresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);
        $form->handleRequest($request);

        $msg = $request->query->get('msg');


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            echo '<script> window.close(); </script>';
            
            return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relation_contact_adresse/edit.html.twig', [
            'msg' => $msg,
            'relation_contact_adresse' => $relationContactAdresse,
            'formRelContactAdresse' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relation_contact_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, RelationContactAdresse $relationContactAdresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relationContactAdresse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($relationContactAdresse);
            $entityManager->flush();
        }

        echo '<script> window.close(); </script>';
            
        return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
    }
}
