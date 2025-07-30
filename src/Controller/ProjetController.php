<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetForm;
use App\Form\RelationProjetSocieteForm;
use App\Repository\ProjetRepository;
use App\Entity\RelationProjetSociete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Loggable\LoggableListener;

#[Route('/projet')]
final class ProjetController extends AbstractController
{
    public function nodeDecorator(array $node): string
    {
        $name = htmlspecialchars($node['name']);
        $url = $this->generateUrl('app_projet_new', ['parent' => $node['id']]);
        return $name . ' <a href="' . $url . '">[+]</a>';
    }








    #[Route(name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {



        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }








    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
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
    public function show(Projet $projet, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $em): Response
    {
        // Find the root ancestor
        //$root = $projet->getRoot() ?? $projet;
        $id = $projet->getId() ?? $projet;
        /*
        $deleteFormHtml = $this->renderView('projet/_delete_form.html.twig', [
            'projet' => $projet, // or the current node if needed
        ]);

        /*
        // Get the HTML tree for this root
        $htmlTree = $projetRepository->childrenHierarchy(
            $root,
            false,
            [
                'decorate' => true,
                'representationField' => 'slug',
                'html' => true,
                //'rootOpen' => '<ul style="float: right;">',
                'nodeDecorator' => function($node) use ($id, $deleteFormHtml) {
                    $name = htmlspecialchars($node['name']);
                    if ($id == $node['id']) {
                        $newUrl = $this->generateUrl('app_projet_new', ['parent' => $node['id']]);
                        return '
                            <div style=" display: flex; flex-direction: row; flex-wrap: nowrap;">
                                <div>
                                    <a class="btn">' . $name . '</a>
                                </div>
                                <div>
                                    <a class="btn create" href="' . $newUrl . '">[+]</a>
                                </div>
                                ' . $deleteFormHtml . '
                            </div>
                        ';
                    }
                    $url = $this->generateUrl('app_projet_show', ['id' => $node['id']]);
                    return '<a class="btn" href="' . $url . '">' . $name . '</a>';
                },
            ],
            true
        );
        */

        // OBJECT HISTORY
            //associated projects seletion
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\Projet'
                ");

                $projetIds[0] = $id;
                foreach($qb->getResult() as $proj){
                    if(isset($proj["data"]["root"]["id"])){
                        if($proj["data"]["root"]["id"] == $id){
                            $projetIds[] = $proj["objectId"];
                        }
                    }
                }
                $projetIds = array_unique($projetIds);


            //associated companies selection
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\RelationProjetSociete'
                ");

                $steIds = [];
                foreach($qb->getResult() as $steProj){
                    if(isset($steProj["data"]["projet"]["id"])){
                        if($steProj["data"]["projet"]["id"] == $id){
                            $steIds[] = $steProj["objectId"];
                        }
                    }
                }
                $steIds = array_unique($steIds);


            //return query
                $qb = $em->createQuery(
                    "SELECT l FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE 
                            ( l.objectClass = 'App\Entity\Projet'
                                AND l.objectId in (:projetIds)) 
                            OR ( l.objectClass = 'App\Entity\RelationProjetSociete'
                                AND l.objectId in (:steIds))
                        ORDER BY l.loggedAt DESC
                ");
                $qb->setParameters([
                    'projetIds' => $projetIds,
                    'steIds' => $steIds,
                ]);
        // END OBJEC HISTORY

        // RELATION PROJ STE
            $relationProjetSociete = new RelationProjetSociete();
            $form = $this->createForm(RelationProjetSocieteForm::class, $relationProjetSociete);
        // END RELATION PROJ STE



        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            //'htmlTree' => $htmlTree,
            'logEntries' => $qb->getResult(),
            'relation_projet_societe' => $relationProjetSociete,
            'formRelProjSte' => $form,
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
