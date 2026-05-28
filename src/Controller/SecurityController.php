<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{




    #[Route(path: '/login', name: 'api_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if(str_contains($_SERVER['HTTP_ACCEPT'], 'text/html')){
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        }

        return new JsonResponse(
            $error ? $error : 'unauthenticated', 
            Response::HTTP_UNAUTHORIZED, 
            [], 
            false
        );
    }




    
    #[Route(path: '/logout', name: 'api_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }




    
    #[Route(path: '/userInfo', name: 'api_userInfo')]
    public function userInfo(#[CurrentUser] Utilisateurs $user,NormalizerInterface $normalizer): Response
    {
        return new JsonResponse(
            $normalizer->normalize($user, 'object', array(AbstractNormalizer::IGNORED_ATTRIBUTES => ['password'])), 
            Response::HTTP_OK, 
            [], 
            false)
        ->setEncodingOptions(
            JSON_UNESCAPED_UNICODE | 
            JSON_UNESCAPED_SLASHES);
    }
}
