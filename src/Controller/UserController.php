<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_user_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\User')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\User')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\User')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\User')->getAssociationTargetClass($atribute)];
        }


        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "user",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    // api return
    private function apiReturn(JsonResponse $response): Response
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
    #[Route('_api',name: 'api_user_index', methods: ['GET'])]
    public function apiIndex(UserRepository $userRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $userRepository->createQueryBuilder('user');



        return $this->apiReturn($apiQueryBuilder->returnIndex($qb, $request, "user"));
    }


            // -show
    #[Route('_api/{id}', name: 'api_user_show', methods: ['GET'])]
    public function apiShow(User $user, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $this->apiReturn($apiQueryBuilder->returnShow($user));
    }


            // -new
    #[Route('_api/new', name: 'api_user_new', methods: ['POST'])]
    public function apiNew(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $response = $this->forward('App\Controller\RegistrationController::register', [
            'request' => $request,
            'userPasswordHasher' => $userPasswordHasher,
            'entityManager' => $entityManager,
        ]);
        



        return $this->apiReturn($response);
    }
    

            // -edit
    #[Route('_api/{id}', name: 'api_user_edit', methods: ['POST'])]
    public function apiEdit(Request $request, User $user, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);



        return $this->apiReturn($apiQueryBuilder->returnEdit($form));
    }
    

            // -delete
    #[Route('_api/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function apiDelete(User $user, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $this->apiReturn($apiQueryBuilder->returnDelete($user));
    }




    //// routes vues
            // -index
    #[Route(name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(int $id): Response
    {
        return $this->render('user/show.html.twig', ['id' => $id]);
    }
}
