<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{



    #[Route('/_api', name: 'api_home')]
    public function apiIndex(EntityManagerInterface $em): Response
    {
        /*
        $classes = array();
        $metas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metas as $meta) {//getFieldNames()
                $classes[] = $meta->getName();
        }//*/
        //$classes = ["user", "projets", "liensFibre", "contacts", "societes", "pays", "communes"];

        $controllers = array();


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
