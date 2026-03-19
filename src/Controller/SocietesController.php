<?php

namespace App\Controller;

use App\Form\SocietesForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Societes;
use App\Repository\SocietesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/societes')]
final class SocietesController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_societes_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Societes')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Societes')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Societes')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Societes')->getAssociationTargetClass($atribute)];
        }


        $societes = new Societes();
        $form = $this->createForm(SocietesForm::class, $societes);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "societes",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_societes_index', methods: ['GET'])]
    public function apiIndex(SocietesRepository $societesRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($societesRepository, $request, "societes");
    }


            // -show
    #[Route('_api/{id}',name: 'api_societes_show', methods: ['GET'])]
    public function apiShow(Societes $societes, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($societes, $request);
    }


            // -new
    #[Route('_api/new', name: 'api_societes_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $societes = new Societes();
        $form = $this->createForm(SocietesForm::class, $societes);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($societes, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_societes_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Societes $societes, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(SocietesForm::class, $societes);
        $form->handleRequest($request);

        
        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_societes_delete', methods: ['DELETE'])]
    public function apiDelete(Societes $societes, ApiQueryBuilder $apiQueryBuilder): Response
    {
        

        return $apiQueryBuilder->returnDelete($societes);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_societes_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('societes/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_societes_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('societes/show.html.twig', ['id' => $id]);
    }








    #[Route('/new', name: 'app_societes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societes = new Societes();
        $form = $this->createForm(SocietesForm::class, $societes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societes);
            $entityManager->flush();

            return $this->redirectToRoute('app_societes_show', ['id' => $societes->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('societes/new.html.twig', [
            'societes' => $societes,
            'form' => $form,
        ]);
    }







    #[Route('/{id}/edit', name: 'app_societes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Societes $societes, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocietesForm::class, $societes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }



        return $this->redirectToRoute('app_societes_show', ['id' => $societes->getId()], Response::HTTP_SEE_OTHER);
    }








    #[Route('/{id}', name: 'app_societes_delete', methods: ['POST'])]
    public function delete(Request $request, Societes $societes, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societes->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($societes);
            $entityManager->flush();
        }



        return $this->redirectToRoute('app_societes_index', [], Response::HTTP_SEE_OTHER);
    }
}