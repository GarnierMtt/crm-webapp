<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController
{



    #[Route('/', name: 'home')]
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
            "marques",
            "types",
            "sites",
            "modeles",
            "taches",
            "materiels",
        ];


        return $this->render('api/api_base.html.twig', [
            'data' => $controllers,
        ]);
    }
}
