<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseForm;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adresse')]
final class AdresseController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_adresse_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Adresse')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Adresse')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Adresse')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Adresse')->getAssociationTargetClass($atribute)];
        }


        $adresse = new Adresse();
        $form = $this->createForm(AdresseForm::class, $adresse);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "adresse",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }

    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_adresse_index', methods: ['GET'])]
    public function apiIndex(AdresseRepository $adresseRepository, SerializerInterface $serializer): JsonResponse
    {
        $adresses = $adresseRepository->findAll();
        $jsonAdresses = $serializer->serialize($adresses, 'json');



        return new JsonResponse($jsonAdresses, Response::HTTP_OK, [], true);
    }

            // -show
    #[Route('_api/{id}',name: 'api_adresse_show', methods: ['GET'])]
    public function apiShow(Adresse $adresse, SerializerInterface $serializer): JsonResponse
    {
        $jsonAdresse = $serializer->serialize($adresse, 'json');



        return new JsonResponse($jsonAdresse, Response::HTTP_OK, [], true);
    }

            // -new
    #[Route('_api/new', name: 'api_adresse_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseForm::class, $adresse);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();
            
            return new Response('', Response::HTTP_CREATED);
        }


        
        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_adresse_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseForm::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return new Response('', Response::HTTP_ACCEPTED);
        }



        return new Response('', Response::HTTP_EXPECTATION_FAILED);
    }

            // -delete
    #[Route('_api/{id}', name: 'api_adresse_delete', methods: ['DELETE'])]
    public function apiDelete(Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($adresse);
        $entityManager->flush();



        return new Response('', Response::HTTP_NO_CONTENT);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_adresse_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('adresse/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('adresse/show.html.twig', ['id' => $id]);
    }
}
