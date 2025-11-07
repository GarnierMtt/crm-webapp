<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Societe;
use App\Form\AdresseForm;
use App\Form\RelationContactAdresseForm;
use App\Repository\AdresseRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adresse')]
final class AdresseController extends AbstractController
{

    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_adresse_index', methods: ['GET'])]
    public function apiIndex(AdresseRepository $adresseRepository, SerializerInterface $serializer): JsonResponse
    {
        $adresses = $adresseRepository->findAll();
        $jsonAdresses = $serializer->serialize($adresses, 'json');



        return new JsonResponse($jsonAdresses, Response::HTTP_OK, [], true);
    }

            // -show
    #[Route('_api/{id}',name: 'api_adresse_show', methods: ['GET'])]
    public function apiShow(Adresse $adresse, SerializerInterface $serializer): JsonResponse
    {
        $jsonAdresse = $serializer->serialize($adresse, 'json');



        return new JsonResponse($jsonAdresse, Response::HTTP_OK, [], true);
    }

            // -new
    #[Route('_api/new', name: 'api_adresse_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseForm::class, $adresse);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();
        }


        return new Response('', Response::HTTP_CREATED);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_adresse_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseForm::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }



        return new Response('', Response::HTTP_ACCEPTED);
    }

            // -delete
    #[Route('_api/{id}', name: 'api_adresse_delete', methods: ['DELETE'])]
    public function apiDelete(Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($adresse);
        $entityManager->flush();



        return new Response('', Response::HTTP_NO_CONTENT);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_adresse_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('adresse/index.html.twig', []);
    }








    #[Route('/new', name: 'app_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SocieteRepository $societeRepository, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $formAdresse = $this->createForm(AdresseForm::class, $adresse);
        $formAdresse->handleRequest($request);


        // Set societe if provided
        $societeId = $request->query->get('societe');
        if ($societeId) {
            $path = $this->redirectToRoute('app_societe_show', ['id' => $societeId], Response::HTTP_SEE_OTHER);
            $societe = $societeRepository->find($societeId);
            if ($societe) {
                $adresse->setSociete($societe);
            }
        }

        if ($formAdresse->isSubmitted() && $formAdresse->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();

            if ($societe) {
                return $this->redirectToRoute('app_societe_show', ['id' => $societeId], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_adresse_show', ['id' => $adresse->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('adresse/new.html.twig', [
            'adresse' => $adresse,
            'formAdresse' => $formAdresse,
        ]);
    }








    #[Route('/{id}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {


        // FORM PROJ
            $formRelContactAdresse = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);


        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
            'formRelContactAdresse' => $formRelContactAdresse,
        ]);
    }








    #[Route('/{id}/edit', name: 'app_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $formAdresse = $this->createForm(AdresseForm::class, $adresse);
        $formAdresse->handleRequest($request);

        if ($formAdresse->isSubmitted() && $formAdresse->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adresse_show', ['id' => $adresse->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'formAdresse' => $formAdresse,
        ]);
    }








    #[Route('/{id}', name: 'app_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }


        
        return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
    }
}
