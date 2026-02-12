<?php

namespace App\Controller;

use App\Form\UtilisateursForm;
use App\Entity\Utilisateurs;
use App\Security\EmailVerifier;
use App\Repository\UtilisateursRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

//include "ResetPasswordController.php";
use Symfony\Component\Mailer\MailerInterface;

class RegistrationController extends AbstractController
{
    private $resetPasswordController;

    public function __construct(private EmailVerifier $emailVerifier,ResetPasswordController $resetPasswordController)
    {
        $this->resetPasswordController = $resetPasswordController;
    }








    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursForm::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // generate random password_get_info   
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890*.,!?-_';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 16; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }

            /** @var string $plainPassword */
            $plainPassword = implode($pass); //turn the array into a string

            // encode the plain password
            $utilisateur->setPassword($userPasswordHasher->hashPassword($utilisateur, $plainPassword));

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $utilisateur,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@horten.fr', 'SupportMailBot'))
                    ->to((string) $utilisateur->getMel())
                    ->subject('Compte CRM - Horten')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email


            
            return new JsonResponse('', Response::HTTP_CREATED, [], false);
        }

        return new JsonResponse('', Response::HTTP_EXPECTATION_FAILED, [], false);
    }








    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UtilisateursRepository $utilisateursRepository, MailerInterface $mailer): Response
    {
        $id = $request->query->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('Error');
        }
        
        /** @var Utilisateurs $utilisateur */
        $utilisateur = $utilisateursRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $utilisateur) {
            return $this->redirectToRoute('Error');
        }

        // validate email confirmation link, sets Utilisateurs::actif=true and persists
        try {    
            $this->emailVerifier->handleEmailConfirmation($request, $utilisateur);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('Error');
        }

        $mel = $utilisateur->getMel();


        
        return $this->resetPasswordController->processSendingPasswordResetEmail($mel, $mailer);
    }
}
