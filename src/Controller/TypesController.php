<?php

namespace App\Controller;

use App\Form\TypesForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Types;
use App\Repository\TypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/types')]
final class TypesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_types_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Types')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Types')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Types')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Types')->getAssociationTargetClass($atribute)];
        }


        $types = new Types();
        $form = $this->createForm(TypesForm::class, $types);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "types",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_types_index', methods: ['GET'])]
    public function apiIndex(TypesRepository $typesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($typesRepository, $request, "types");
    }


            // -show
    #[Route('_api/{id}',name: 'api_types_show', methods: ['GET'])]
    public function apiShow(Types $types, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($types, $request);
    }


            // -new
    #[Route('_api/new', name: 'api_types_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $types = new Types();
        $form = $this->createForm(TypesForm::class, $types);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($types, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_types_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Types $types, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(TypesForm::class, $types);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_types_delete', methods: ['DELETE'])]
    public function apiDelete(Types $types, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($types);
    }
}
