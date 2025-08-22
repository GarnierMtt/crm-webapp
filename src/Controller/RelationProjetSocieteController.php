<?php

namespace App\Controller;

use App\Entity\RelationProjetSociete;
use App\Form\RelationProjetSocieteForm;
use App\Repository\RelationProjetSocieteRepository;
use App\Repository\ProjetRepository;
use App\Repository\SocieteRepository;
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
    public function new(Request $request, ProjetRepository $projetRepository, SocieteRepository $societeRepository, EntityManagerInterface $entityManager): Response
    {
        $relationProjetSociete = new RelationProjetSociete();
        $form = $this->createForm(RelationProjetSocieteForm::class, $relationProjetSociete);
        $form->handleRequest($request);


        // Set projet if provided
        $projetId = $request->query->get('projet');
        if ($projetId) {
            $projet = $projetRepository->find($projetId);
            if ($projet) {
                $relationProjetSociete->setProjet($projet);

                // Set societe if provided
                $societeId = $request->query->get('societe');
                if ($societeId) {
                    $societe = $societeRepository->find($societeId);
                    if ($societe) {
                        $relationProjetSociete->setSociete($societe);
                        $relationProjetSociete->setRole('Client');
                    }
                }
            }
        }

        if (($form->isSubmitted() && $form->isValid()) || $societe) {
            $entityManager->persist($relationProjetSociete);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_show', ['id' => $projetId], Response::HTTP_SEE_OTHER);
        }

        throw new \Exception('Something went wrong!');
    }

    #[Route('/{id}', name: 'app_relation_projet_societe_show', methods: ['GET'])]
    public function show(RelationProjetSociete $relationProjetSociete): Response
    {
        return $this->render('relation_projet_societe/show.html.twig', [
            'relation_projet_societe' => $relationProjetSociete,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relation_projet_societe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RelationProjetSociete $relationProjetSociete, ProjetRepository $projetRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationProjetSocieteForm::class, $relationProjetSociete);
        $form->handleRequest($request);

        // Set msg if provided
        $msg = $request->query->get('msg');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            echo '<script> window.close(); </script>';

            return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relation_projet_societe/edit.html.twig', [
            'msg' => $msg,
            'relation_projet_societe' => $relationProjetSociete,
            'formRelProjSte' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relation_projet_societe_delete', methods: ['POST'])]
    public function delete(Request $request, RelationProjetSociete $relationProjetSociete, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relationProjetSociete->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($relationProjetSociete);
            $entityManager->flush();
        }

        echo '<script> window.close(); </script>';
            
        return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
    }
}
