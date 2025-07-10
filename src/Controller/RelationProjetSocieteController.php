<?php

namespace App\Controller;

use App\Entity\RelationProjetSociete;
use App\Form\RelationProjetSocieteForm;
use App\Repository\RelationProjetSocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relation/projet/societe')]
final class RelationProjetSocieteController extends AbstractController
{
    #[Route(name: 'app_relation_projet_societe_index', methods: ['GET'])]
    public function index(RelationProjetSocieteRepository $relationProjetSocieteRepository): Response
    {
        return $this->render('relation_projet_societe/index.html.twig', [
            'relation_projet_societes' => $relationProjetSocieteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_relation_projet_societe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $relationProjetSociete = new RelationProjetSociete();
        $form = $this->createForm(RelationProjetSocieteForm::class, $relationProjetSociete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relationProjetSociete);
            $entityManager->flush();

            return $this->redirectToRoute('app_relation_projet_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relation_projet_societe/new.html.twig', [
            'relation_projet_societe' => $relationProjetSociete,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relation_projet_societe_show', methods: ['GET'])]
    public function show(RelationProjetSociete $relationProjetSociete): Response
    {
        return $this->render('relation_projet_societe/show.html.twig', [
            'relation_projet_societe' => $relationProjetSociete,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relation_projet_societe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RelationProjetSociete $relationProjetSociete, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationProjetSocieteForm::class, $relationProjetSociete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_relation_projet_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relation_projet_societe/edit.html.twig', [
            'relation_projet_societe' => $relationProjetSociete,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relation_projet_societe_delete', methods: ['POST'])]
    public function delete(Request $request, RelationProjetSociete $relationProjetSociete, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relationProjetSociete->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($relationProjetSociete);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_relation_projet_societe_index', [], Response::HTTP_SEE_OTHER);
    }
}
