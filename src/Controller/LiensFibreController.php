<?php

namespace App\Controller;

use App\Form\LiensFibreForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\LiensFibre;
use App\Repository\LiensFibreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/lien/fibre')]
final class LiensFibreController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_liensFibre_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getAssociationTargetClass($atribute)];
        }


        $liensfibre = new LiensFibre();
        $form = $this->createForm(LiensFibreForm::class, $liensfibre);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "liensFibre",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_liensFibre_index', methods: ['GET'])]
    public function apiIndex(LiensFibreRepository $liensFibreRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $liensFibreRepository->createQueryBuilder('liensFibre');
        $qb->leftJoin('liensFibre.fk_projets', 'projets')
           ->addSelect('projets')
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "liensFibre");
    }


            // -show
    #[Route('_api/{id}',name: 'api_liensFibre_show', methods: ['GET'])]
    public function apiShow(LiensFibre $liensFibre, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($liensFibre);
    }


            // -new
    #[Route('_api/new', name: 'api_liensFibre_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $liensFibre = new LiensFibre();
        $form = $this->createForm(LiensFibreForm::class, $liensFibre);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($liensFibre, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_liensFibre_edit', methods: ['POST'])]
    public function apiEdit(Request $request, LiensFibre $liensFibre, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(LiensFibreForm::class, $liensFibre);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_liensFibre_delete', methods: ['DELETE'])]
    public function apiDelete(LiensFibre $liensFibre, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($liensFibre);
    }
}
