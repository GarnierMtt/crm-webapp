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
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relation/contact/adresse')]
final class RelationContactAdresseController extends AbstractController
{
    #[Route('/new', name: 'app_relation_contact_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $relationContactAdresse = new RelationContactAdresse();
        $form = $this->createForm(RelationContactAdresseForm::class, $relationContactAdresse);
        $form->handleRequest($request);
        $path = $this->redirectToRoute('app_relation_contact_adresse_index', [], Response::HTTP_SEE_OTHER);


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
            'formRelSteAdresse' => $form,
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
            'formRelSteAdresse' => $form,
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
