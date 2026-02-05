<?php

namespace App\Controller;

use App\Form\PaysForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Pays;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pays')]
final class PaysController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_pays_documentation', methods: ['GET'])]
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



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "pays",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_pays_index', methods: ['GET'])]
    public function apiIndex(PaysRepository $paysRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $paysRepository->createQueryBuilder('pays');
        $qb->leftJoin('pays.fk_communes', 'communes')
           ->addSelect('communes')
           /*->leftJoin('pays.adresses', 'adresses')
           ->addSelect('adresses')
           ->leftJoin('adresses.adresse', 'adresse')
           ->addSelect('adresse')//*/
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "pays");
    }


            // -show
    #[Route('_api/{id}',name: 'api_pays_show', methods: ['GET'])]
    public function apiShow(Pays $pays, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($pays);
    }


            // -new
    #[Route('_api/new', name: 'api_pays_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $pays = new Pays();
        $form = $this->createForm(PaysForm::class, $pays);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($pays, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_pays_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Pays $pays, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(PaysForm::class, $pays);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_pays_delete', methods: ['DELETE'])]
    public function apiDelete(Pays $pays, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($pays);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_pays_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pays/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_pays_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('pays/show.html.twig', ['id' => $id]);
    }
}
