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
    #[Route('_docs',name: 'api_projets_documentation', methods: ['GET'])]
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



        return $this->render('api/obj_index.html.twig', [
            'class' => "projets",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('', name: 'api_projets_index', methods: ['GET'])]
    public function apiIndex(ProjetsRepository $projetsRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($projetsRepository, $request, "projets");
    }


            // -show
    #[Route('/{id}', name: 'api_projets_show', methods: ['GET'])]
    public function apiShow(Projets $projets, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($projets, $request);
    }


            // -new
    #[Route('/new', name: 'api_projets_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $projets = new Projets();
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($projets, $form);
    }


            // -edit
    #[Route('/{id}', name: 'api_projets_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Projets $projets, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ProjetsForm::class, $projets);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('/{id}', name: 'api_projets_delete', methods: ['DELETE'])]
    public function apiDelete(Projets $projets, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnDelete($projets);
    }
}
