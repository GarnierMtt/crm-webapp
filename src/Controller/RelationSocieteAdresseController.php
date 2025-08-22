<?php

namespace App\Controller;

use App\Entity\RelationSocieteAdresse;
use App\Form\RelationSocieteAdresseForm;
use App\Repository\RelationSocieteAdresseRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relation/societe/adresse')]
final class RelationSocieteAdresseController extends AbstractController
{
    #[Route('/new', name: 'app_relation_societe_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SocieteRepository $societeRepository, EntityManagerInterface $entityManager): Response
    {
        $relationSocieteAdresse = new RelationSocieteAdresse();
        $form = $this->createForm(RelationSocieteAdresseForm::class, $relationSocieteAdresse);
        $form->handleRequest($request);
        $path = $this->redirectToRoute('app_relation_societe_adresse_index', [], Response::HTTP_SEE_OTHER);


        // Set societe if provided
        $societeId = $request->query->get('societe');
        if ($societeId) {
            $path = $this->redirectToRoute('app_societe_show', ['id' => $societeId], Response::HTTP_SEE_OTHER);
            $societe = $societeRepository->find($societeId);
            if ($societe) {
                $relationSocieteAdresse->setSociete($societe);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relationSocieteAdresse);
            $entityManager->flush();

            return $path;
        }

        return $this->render('relation_societe_adresse/new.html.twig', [
            'relation_societe_adresse' => $relationSocieteAdresse,
            'formRelSteAdresse' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_relation_societe_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RelationSocieteAdresse $relationSocieteAdresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationSocieteAdresseForm::class, $relationSocieteAdresse);
        $form->handleRequest($request);

        $msg = $request->query->get('msg');


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            echo '<script> window.close(); </script>';
            
            return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('relation_societe_adresse/edit.html.twig', [
            'msg' => $msg,
            'relation_societe_adresse' => $relationSocieteAdresse,
            'formRelSteAdresse' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_relation_societe_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, RelationSocieteAdresse $relationSocieteAdresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relationSocieteAdresse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($relationSocieteAdresse);
            $entityManager->flush();
        }

        echo '<script> window.close(); </script>';
            
        return $this->redirectToRoute('#', [], Response::HTTP_SEE_OTHER);
    }
}
