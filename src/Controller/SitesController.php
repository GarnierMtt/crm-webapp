<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Form\SitesForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\SitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/sites')]
final class SitesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_sites_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Sites')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Sites')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Sites')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Sites')->getAssociationTargetClass($atribute)];
        }


        $sites = new Sites();
        $form = $this->createForm(SitesForm::class, $sites);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "sites",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_sites_index', methods: ['GET'])]
    public function apiIndex(SitesRepository $sitesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $sitesRepository->createQueryBuilder('sites');
        $qb->leftJoin('sites.fk_societes', 'societes')
           ->addSelect('societes')
           ->leftJoin('sites.fk_communes', 'communes')
           ->addSelect('communes')
           ->leftJoin('communes.fk_pays', 'pays')
           ->addSelect('pays')
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "sites");
    }


            // -show
    #[Route('_api/{id}',name: 'api_sites_show', methods: ['GET'])]
    public function apiShow(Sites $sites, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($sites);
    }


            // -new
    #[Route('_api/new', name: 'api_sites_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $sites = new Sites();
        $form = $this->createForm(SitesForm::class, $sites);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($sites, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_sites_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Sites $sites, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(SitesForm::class, $sites);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_sites_delete', methods: ['DELETE'])]
    public function apiDelete(Sites $sites, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($sites);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_sites_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('sites/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_sites_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('sites/show.html.twig', ['id' => $id]);
    }
}
