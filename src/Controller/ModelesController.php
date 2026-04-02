<?php

namespace App\Controller;

use App\Entity\Modeles;
use App\Form\ModelesForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\ModelesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/modeles')]
final class ModelesController extends AbstractController
{
    //// api documentation
    #[Route('_docs',name: 'api_modeles_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Modeles')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Modeles')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Modeles')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Modeles')->getAssociationTargetClass($atribute)];
        }


        $modeles = new Modeles();
        $form = $this->createForm(ModelesForm::class, $modeles);
        $form->handleRequest($request);



        return $this->render('api/obj_index.html.twig', [
            'class' => "modeles",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('',name: 'api_modeles_index', methods: ['GET'])]
    public function apiIndex(ModelesRepository $modelesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($modelesRepository, $request, "modeles");
    }


            // -show
    #[Route('/{id}',name: 'api_modeles_show', methods: ['GET'])]
    public function apiShow(Modeles $modeles, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($modeles, $request);
    }


            // -new
    #[Route('/new', name: 'api_modeles_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $modeles = new Modeles();
        $form = $this->createForm(ModelesForm::class, $modeles);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($modeles, $form);
    }


            // -edit
    #[Route('/{id}', name: 'api_modeles_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Modeles $modeles, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ModelesForm::class, $modeles);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('/{id}', name: 'api_modeles_delete', methods: ['DELETE'])]
    public function apiDelete(Modeles $modeles, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($modeles);
    }
}
