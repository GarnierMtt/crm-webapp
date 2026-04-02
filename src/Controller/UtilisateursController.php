<?php

namespace App\Controller;

use App\Form\UtilisateursForm;
use App\Utils\ApiQueryBuilder;
use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/utilisateurs')]
final class UtilisateursController extends AbstractController
{
    private $ignored = ['password'];

    //// api documentation
    #[Route('_docs',name: 'api_utilisateurs_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Utilisateurs')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Utilisateurs')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Utilisateurs')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Utilisateurs')->getAssociationTargetClass($atribute)];
        }


        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursForm::class, $utilisateur);
        $form->handleRequest($request);



        return $this->render('api/obj_index.html.twig', [
            'class' => "utilisateurs",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('',name: 'api_utilisateurs_index', methods: ['GET'])]
    public function apiIndex(UtilisateursRepository $utilisateursRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        
            //AbstractNormalizer::ATTRIBUTES => ['id', 'password'];
            //AbstractNormalizer::IGNORED_ATTRIBUTES => ['password'];

            //$utilisateursRepository->FindBy([]);



        return $apiQueryBuilder->returnIndex($utilisateursRepository, $request, "utilisateurs", $this->ignored);
    }


            // -show
    #[Route('/{id}', name: 'api_utilisateurs_show', methods: ['GET'])]
    public function apiShow(Utilisateurs $utilisateur, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnShow($utilisateur, $request, $this->ignored);
    }


            // -new
    #[Route('/new', name: 'api_utilisateurs_new', methods: ['POST'])]
    public function apiNew(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $response = $this->forward('App\Controller\RegistrationController::register', [
            'request' => $request,
            'userPasswordHasher' => $userPasswordHasher,
            'entityManager' => $entityManager,
        ]);
        



        return $apiQueryBuilder->apiReturn($response);
    }
    

            // -edit
    #[Route('/{id}', name: 'api_utilisateurs_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Utilisateurs $utilisateur, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(UtilisateursForm::class, $utilisateur);
        $form->handleRequest($request);



        return $apiQueryBuilder->returnEdit($form);
    }
    

            // -delete
    #[Route('/{id}', name: 'api_utilisateurs_delete', methods: ['DELETE'])]
    public function apiDelete(Utilisateurs $utilisateur, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnDelete($utilisateur);
    }
}
