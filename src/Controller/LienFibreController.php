<?php

namespace App\Controller;

use App\Entity\LienFibre;
use App\Form\LienFibreForm;
use App\Repository\LienFibreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lien/fibre')]
final class LienFibreController extends AbstractController
{
    #[Route(name: 'app_lien_fibre_index', methods: ['GET'])]
    public function index(LienFibreRepository $lienFibreRepository): Response
    {
        return $this->render('lien_fibre/index.html.twig', [
            'lien_fibres' => $lienFibreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lien_fibre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lienFibre = new LienFibre();
        $form = $this->createForm(LienFibreForm::class, $lienFibre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lienFibre);
            $entityManager->flush();

            return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lien_fibre/new.html.twig', [
            'lien_fibre' => $lienFibre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lien_fibre_show', methods: ['GET'])]
    public function show(LienFibre $lienFibre): Response
    {
        return $this->render('lien_fibre/show.html.twig', [
            'lien_fibre' => $lienFibre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lien_fibre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LienFibre $lienFibre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LienFibreForm::class, $lienFibre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lien_fibre/edit.html.twig', [
            'lien_fibre' => $lienFibre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lien_fibre_delete', methods: ['POST'])]
    public function delete(Request $request, LienFibre $lienFibre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lienFibre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lienFibre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
    }
}
