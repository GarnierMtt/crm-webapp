<?php

namespace App\Controller;

use App\Form\MaterielsForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Materiels;
use App\Repository\MaterielsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/materiels')]
final class MaterielsController extends AbstractController
{
    //// api documentation
    #[Route('_docs',name: 'api_materiels_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Materiels')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Materiels')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Materiels')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Materiels')->getAssociationTargetClass($atribute)];
        }


        $materiels = new Materiels();
        $form = $this->createForm(MaterielsForm::class, $materiels);
        $form->handleRequest($request);



        return $this->render('api/obj_index.html.twig', [
            'class' => "materiels",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('',name: 'api_materiels_index', methods: ['GET'])]
    public function apiIndex(MaterielsRepository $materielsRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($materielsRepository, $request, "materiels");
    }


            // -show
    #[Route('/{id}',name: 'api_materiels_show', methods: ['GET'])]
    public function apiShow(Materiels $materiels, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($materiels, $request);
    }


            // -new
    #[Route('/new', name: 'api_materiels_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $materiels = new Materiels();
        $form = $this->createForm(MaterielsForm::class, $materiels);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($materiels, $form);
    }


            // -edit
    #[Route('/{id}', name: 'api_materiels_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Materiels $materiels, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(MaterielsForm::class, $materiels);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('/{id}', name: 'api_materiels_delete', methods: ['DELETE'])]
    public function apiDelete(Materiels $materiels, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($materiels);
    }
}
