<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetForm;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projet')]
final class ProjetController extends AbstractController
{
    public function nodeDecorator(array $node): string
    {
        $name = htmlspecialchars($node['name']);
        $url = $this->generateUrl('app_projet_new', ['parent' => $node['id']]);
        return $name . ' <a href="' . $url . '">[+ Add Child]</a>';
    }

    #[Route(name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        $rootProjets = $projetRepository->findBy(['parent' => null]);

        return $this->render('projet/index.html.twig', [
            'projets' => $rootProjets,
        ]);
    }

    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjetRepository $projetRepository, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();

        // Set parent if provided
        $parentId = $request->query->get('parent');
        if ($parentId) {
            $parent = $projetRepository->find($parentId);
            if ($parent) {
                $projet->setParent($parent);
            }
        }

        $form = $this->createForm(ProjetForm::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet, ProjetRepository $projetRepository): Response
    {
        // Find the root ancestor
        $root = $projet->getRoot() ?? $projet;

        // Get the HTML tree for this root
        $htmlTree = $projetRepository->childrenHierarchy(
            $root,
            false,
            [
                'decorate' => true,
                'includeNode' => true,
                'rootOpen' => '<ul>',
                'rootClose' => '</ul>',
                'childOpen' => '<li>',
                'childClose' => '</li>',
                'nodeDecorator' => [$this, 'nodeDecorator'],
            ]
        );

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'root' => $root,
            'htmlTree' => $htmlTree,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetForm::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
    }
}
