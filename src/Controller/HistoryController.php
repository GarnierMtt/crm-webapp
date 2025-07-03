<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Gedmo\Loggable\Entity\LogEntry;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Loggable\LoggableListener;
use App\Entity\Projet;


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
