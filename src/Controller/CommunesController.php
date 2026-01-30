<?php

namespace App\Controller;

use App\Entity\Communes;
use App\Form\CommunesForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\CommunesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/communes')]
final class CommunesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_communes_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Communes')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Communes')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Communes')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Communes')->getAssociationTargetClass($atribute)];
        }


        $communes = new Communes();
        $form = $this->createForm(CommunesForm::class, $communes);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "communes",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    // api return
    private function apiReturn($response): Response
    {
        // response
        if($_SERVER["HTTP_ACCEPT"] == "text/html"){
            $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
            return $this->render('api/api_obj_response.html.twig', [
                'data' => $response,
            ]);
        }
        return $response;
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_communes_index', methods: ['GET'])]
    public function apiIndex(CommunesRepository $communesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $communesRepository->createQueryBuilder('communes');
        $qb->leftJoin('communes.societe', 'societe')
           ->addSelect('societe')
           /*->leftJoin('communes.adresses', 'adresses')
           ->addSelect('adresses')
           ->leftJoin('adresses.adresse', 'adresse')
           ->addSelect('adresse')//*/
           ;

        
        return $this->apiReturn($apiQueryBuilder->returnIndex($qb, $request, "communes"));
    }


            // -show
    #[Route('_api/{id}',name: 'api_communes_show', methods: ['GET'])]
    public function apiShow(Communes $communes, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $this->apiReturn($apiQueryBuilder->returnShow($communes));
    }


            // -new
    #[Route('_api/new', name: 'api_communes_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $communes = new Communes();
        $form = $this->createForm(CommunesForm::class, $communes);
        $form->handleRequest($request);


        return $this->apiReturn($apiQueryBuilder->returnNew($communes, $form));
    }


            // -edit
    #[Route('_api/{id}', name: 'api_communes_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Communes $communes, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(CommunesForm::class, $communes);
        $form->handleRequest($request);


        return $this->apiReturn($apiQueryBuilder->returnEdit($form));
    }


            // -delete
    #[Route('_api/{id}', name: 'api_communes_delete', methods: ['DELETE'])]
    public function apiDelete(Communes $communes, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $this->apiReturn($apiQueryBuilder->returnDelete($communes));
    }




    //// routes vues
            // -index
    #[Route(name: 'app_communes_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('communes/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_communes_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('communes/show.html.twig', ['id' => $id]);
    }
}
