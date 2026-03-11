<?php

namespace App\Controller;

use App\Form\MarquesForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Marques;
use App\Repository\MarquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/marques')]
final class MarquesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_marques_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Marques')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Marques')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Marques')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Marques')->getAssociationTargetClass($atribute)];
        }


        $marques = new Marques();
        $form = $this->createForm(MarquesForm::class, $marques);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "marques",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_marques_index', methods: ['GET'])]
    public function apiIndex(MarquesRepository $marquesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $marquesRepository->createQueryBuilder('marques');
        $qb->leftJoin('marques.fk_modeles', 'modeles')
           ->addSelect('modeles')
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "marques");
    }


            // -show
    #[Route('_api/{id}',name: 'api_marques_show', methods: ['GET'])]
    public function apiShow(Marques $marques, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($marques);
    }


            // -new
    #[Route('_api/new', name: 'api_marques_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $marques = new Marques();
        $form = $this->createForm(MarquesForm::class, $marques);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($marques, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_marques_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Marques $marques, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(MarquesForm::class, $marques);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_marques_delete', methods: ['DELETE'])]
    public function apiDelete(Marques $marques, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($marques);
    }
}
