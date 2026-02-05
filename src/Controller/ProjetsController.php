<?php

namespace App\Controller;

use App\Form\ProjetsForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Projets;
use App\Repository\ProjetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/projets')]
final class ProjetsController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_projets_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Projets')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Projets')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Projets')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Projets')->getAssociationTargetClass($atribute)];
        }


        $projets = new Projets();
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "projets",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api', name: 'api_projets_index', methods: ['GET'])]
    public function apiIndex(ProjetsRepository $projetsRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $projetsRepository->createQueryBuilder('projets');
        $qb->leftJoin('projets.fk_liens_fibre', 'liens_fibre')
           ->addSelect('liens_fibre')
           ;


        return $apiQueryBuilder->returnIndex($qb, $request, "projets");
    }


            // -show
    #[Route('_api/{id}', name: 'api_projets_show', methods: ['GET'])]
    public function apiShow(Projets $projets, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($projets);
    }


            // -new
    #[Route('_api/new', name: 'api_projets_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $projets = new Projets();
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($projets, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_projets_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Projets $projets, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_projets_delete', methods: ['DELETE'])]
    public function apiDelete(Projets $projets, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnDelete($projets);
    }




    //// routes vues
            // -index
    public function nodeDecorator(array $node): string
    {
        $name = htmlspecialchars($node['name']);
        $url = $this->generateUrl('app_projets_new', ['parent' => $node['id']]);
        return $name . ' <a href="' . $url . '">[+]</a>';
    }








    #[Route(name: 'app_projets_index', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): Response
    {



        return $this->render('projets/index.html.twig', [
            'projets' => $projetsRepository->findAll(),
        ]);
    }








    #[Route('/new', name: 'app_projets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projets = new Projets();
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projets);
            $entityManager->flush();

            // if societe is provided, create association
            $societeId = $request->query->get('societe');
            if ($societeId) {
                return $this->redirectToRoute('app_relation_projets_societe_new', ['societe' => $societeId, 'projets' => $projets->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('projets/new.html.twig', [
            'projets' => $projets,
            'formProj' => $form,
        ]);
    }






    

    #[Route('/{id}', name: 'app_projets_show', methods: ['GET'])]
    public function show(Projets $projets, Request $request, ProjetsRepository $projetsRepository, EntityManagerInterface $em): Response
    {
        // Find the root ancestor
        //$root = $projets->getRoot() ?? $projets;
        $id = $projets->getId() ?? $projets;
        $formProj = $this->createForm(ProjetsForm::class, $projets);
        /*
        $deleteFormHtml = $this->renderView('projets/_delete_form.html.twig', [
            'projets' => $projets, // or the current node if needed
        ]);

        /*
        // Get the HTML tree for this root
        $htmlTree = $projetsRepository->childrenHierarchy(
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
                        $newUrl = $this->generateUrl('app_projets_new', ['parent' => $node['id']]);
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
                    $url = $this->generateUrl('app_projets_show', ['id' => $node['id']]);
                    return '<a class="btn" href="' . $url . '">' . $name . '</a>';
                },
            ],
            true
        );
        */

        // OBJECT HISTORY
            //associated companies selection
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\RelationProjetsSociete'
                ");

                $steIds = [];
                foreach($qb->getResult() as $steProj){
                    if(isset($steProj["data"]["projets"]["id"])){
                        if($steProj["data"]["projets"]["id"] == $id){
                            $steIds[] = $steProj["objectId"];
                        }
                    }
                }
                $steIds = array_unique($steIds);


            //return query
                $qb = $em->createQuery(
                    "SELECT l FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE 
                            ( l.objectClass = 'App\Entity\Projets'
                                AND l.objectId = (:projId)) 
                            OR ( l.objectClass = 'App\Entity\RelationProjetsSociete'
                                AND l.objectId in (:steIds))
                        ORDER BY l.loggedAt DESC
                ");
                $qb->setParameters([
                    'projId' => $id,
                    'steIds' => $steIds,
                ]);
        // END OBJEC HISTORY

        // RELATION PROJ STE
          /*  $formRelProjSte = $this->createForm(RelationProjetsSocieteForm::class, new RelationProjetsSociete());
        // END RELATION PROJ STE */



        return $this->render('projets/show.html.twig', [
            'projets' => $projets,
            //'htmlTree' => $htmlTree,
            'logEntries' => $qb->getResult(),
            //'formRelProjSte' => $formRelProjSte,
            'formProj' => $formProj,
        ]);
    }






    

    #[Route('/{id}/edit', name: 'app_projets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }



        return $this->redirectToRoute('app_projets_show', ['id' => $projets->getId()], Response::HTTP_SEE_OTHER);
    }








    #[Route('/{id}', name: 'app_projets_delete', methods: ['POST'])]
    public function delete(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projets->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projets);
            $entityManager->flush();
        }


        
        return $this->redirectToRoute('app_projets_index', [], Response::HTTP_SEE_OTHER);
    }
}
