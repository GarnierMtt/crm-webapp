<?php

namespace App\Controller;

use Gedmo\Loggable\Entity\LogEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/historique')]
final class HistoryController extends AbstractController
{








    #[Route(name: 'app_history_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(LogEntry::class);


        
        return $this->render('history/index.html.twig', [
            'logEntries' => $repo->findBy(
                [],
                ['loggedAt' => 'DESC'],
            ),
        ]);
    }
}
