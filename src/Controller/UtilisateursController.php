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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/utilisateurs')]
final class UtilisateursController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_utilisateurs_documentation', methods: ['GET'])]
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



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "utilisateurs",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_utilisateurs_index', methods: ['GET'])]
    public function apiIndex(UtilisateursRepository $utilisateursRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        /*
        // base query
        $qb = $utilisateursRepository->createQueryBuilder('utilisateurs');
        $qb->leftJoin('utilisateurs.fk_taches', 'taches')
           ->addSelect('taches')
           ;

                AbstractNormalizer::ATTRIBUTES => ['id', 'password'],AbstractNormalizer::IGNORED_ATTRIBUTES => ['password'],;

                $utilisateursRepository->FindBy([]);

        //return $apiQueryBuilder->returnIndex($qb, $request, "utilisateurs");
        //*/
        //$utilisateursRepository->FindBy([]);


        return $apiQueryBuilder->returnTestIndex($utilisateursRepository, $request, [ AbstractNormalizer::IGNORED_ATTRIBUTES => ['password'],]);
    }


            // -show
    #[Route('_api/{id}', name: 'api_utilisateurs_show', methods: ['GET'])]
    public function apiShow(Utilisateurs $utilisateur, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnShow($utilisateur, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['password'],]);
    }


            // -new
    #[Route('_api/new', name: 'api_utilisateurs_new', methods: ['POST'])]
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
    #[Route('_api/{id}', name: 'api_utilisateurs_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Utilisateurs $utilisateur, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(UtilisateursForm::class, $utilisateur);
        $form->handleRequest($request);



        return $apiQueryBuilder->returnEdit($form);
    }
    

            // -delete
    #[Route('_api/{id}', name: 'api_utilisateurs_delete', methods: ['DELETE'])]
    public function apiDelete(Utilisateurs $utilisateur, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnDelete($utilisateur);
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
