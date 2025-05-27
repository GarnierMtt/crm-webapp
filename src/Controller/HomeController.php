<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        /** @var user $user */
        /*

            use App\Entity\User;
            use App\Security\EmailVerifier;
            use Symfony\Bridge\Twig\Mime\TemplatedEmail;
            use Symfony\Component\Mime\Address;



        $user = $this->getUser();
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@horten.fr', 'SupportMailBot'))
                    ->to((string) $user->getEmail())
                    ->subject('Compte CRM - Horten')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        //*/
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
