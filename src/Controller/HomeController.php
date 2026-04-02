<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HomeController extends AbstractController
{



    #[Route('/', name: 'home')]
    public function apiIndex(): Response
    {
        if(str_contains($_SERVER["HTTP_ACCEPT"], "text/html")){
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


            return $this->render('api/base.html.twig', [
                'data' => $controllers,
            ]);
        }

        return new JsonResponse(
            '', 
            Response::HTTP_OK, 
            [], 
            false
        );
    }
}
