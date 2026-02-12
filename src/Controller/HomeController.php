<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController
{



    #[Route('/_api', name: 'api_home')]
    public function apiIndex(): Response
    {
        $controllers = [
            "utilisateurs", 
            "projets", 
            "liensFibre", 
            "contacts", 
            "societes", 
            "pays", 
            "communes",
        ];


        return $this->render('api/api_base.html.twig', [
            'data' => $controllers,
        ]);
    }



    #[Route('/')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', []);
    }
}
