<?php

namespace App\Controller;

use App\Form\PaysForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Pays;
use App\Repository\PaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/pays')]
final class PaysController extends AbstractController
{
    //// api documentation
    #[Route('_docs',name: 'api_pays_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Pays')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Pays')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Pays')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Pays')->getAssociationTargetClass($atribute)];
        }


        $pays = new Pays();
        $form = $this->createForm(PaysForm::class, $pays);
        $form->handleRequest($request);



        return $this->render('api/obj_index.html.twig', [
            'class' => "pays",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('',name: 'api_pays_index', methods: ['GET'])]
    public function apiIndex(PaysRepository $paysRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($paysRepository, $request, "pays");
    }


            // -show
    #[Route('/{id}',name: 'api_pays_show', methods: ['GET'])]
    public function apiShow(Pays $pays, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($pays, $request);
    }


            // -new
    #[Route('/new', name: 'api_pays_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $pays = new Pays();
        $form = $this->createForm(PaysForm::class, $pays);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($pays, $form);
    }


            // -edit
    #[Route('/{id}', name: 'api_pays_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Pays $pays, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(PaysForm::class, $pays);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('/{id}', name: 'api_pays_delete', methods: ['DELETE'])]
    public function apiDelete(Pays $pays, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($pays);
    }
}
