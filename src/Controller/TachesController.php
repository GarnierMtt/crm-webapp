<?php

namespace App\Controller;

use App\Form\TachesForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Taches;
use App\Repository\TachesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/taches')]
final class TachesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_taches_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Taches')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Taches')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Taches')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Taches')->getAssociationTargetClass($atribute)];
        }


        $taches = new Taches();
        $form = $this->createForm(TachesForm::class, $taches);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "taches",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_taches_index', methods: ['GET'])]
    public function apiIndex(TachesRepository $tachesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $tachesRepository->createQueryBuilder('taches');
        $qb->leftJoin('taches.fk_projets', 'projets')
           ->addSelect('projets')
           ->leftJoin('taches.fk_societes', 'societes')
           ->addSelect('societes')
           ->leftJoin('taches.fk_utilisateurs', 'utilisateurs')
           ->addSelect('utilisateurs')
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "taches");
    }


            // -show
    #[Route('_api/{id}',name: 'api_taches_show', methods: ['GET'])]
    public function apiShow(Taches $taches, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($taches);
    }


            // -new
    #[Route('_api/new', name: 'api_taches_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $taches = new Taches();
        $form = $this->createForm(TachesForm::class, $taches);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($taches, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_taches_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Taches $taches, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(TachesForm::class, $taches);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_taches_delete', methods: ['DELETE'])]
    public function apiDelete(Taches $taches, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($taches);
    }
}
