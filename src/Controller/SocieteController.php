<?php

namespace App\Controller;

use App\Entity\Societe;
use App\Form\SocieteForm;
use App\Form\AdresseForm;
use App\Form\ContactForm;
use App\Form\ProjetForm;
use App\Repository\SocieteRepository;
use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/societe')]
final class SocieteController extends AbstractController
{








    #[Route(name: 'app_societe_index', methods: ['GET'])]
    public function index(SocieteRepository $societeRepository): Response
    {



        return $this->render('societe/index.html.twig', [
            'societes' => $societeRepository->findAll(),
        ]);
    }








    #[Route('/new', name: 'app_societe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteForm::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_show', ['id' => $societe->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('societe/new.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }







    #[Route('/{id}', name: 'app_societe_show', methods: ['GET'])]
    public function show(Societe $societe, EntityManagerInterface $em): Response
    {
        $id = $societe->getId() ?? $societe;
        $formSte = $this->createForm(SocieteForm::class, $societe);
       

        // OBJECT HISTORY
            //associated adresses selection
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\RelationSocieteAdresse'
                ");

                $adrIds = [];
                foreach($qb->getResult() as $adrSte){
                    if(isset($adrSte["data"]["projet"]["id"])){
                        if($adrSte["data"]["projet"]["id"] == $id){
                            $adrIds[] = $adrSte["objectId"];
                        }
                    }
                }
                $adrIds = array_unique($adrIds);


            //return query
                $qb = $em->createQuery(
                    "SELECT l FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE 
                            ( l.objectClass = 'App\Entity\Societe'
                                AND l.objectId = (:steId)) 
                            OR ( l.objectClass = 'App\Entity\RelationSocieteAdresse'
                                AND l.objectId in (:adrIds))
                        ORDER BY l.loggedAt DESC
                ");
                $qb->setParameters([
                    'steId' => $id,
                    'adrIds' => $adrIds,
                ]);
        // END OBJEC HISTORY

        // RELATION STE ADRESSE
            $formAdresse = $this->createForm(AdresseForm::class, new Adresse());
        // END RELATION STE ADRESSE

        // FORM CONTACT
            $formContact = $this->createForm(ContactForm::class, new Contact() );
        // END FORM CONTACT

        // FORM PROJ
            $formProj = $this->createForm(ProjetForm::class, new Projet() );
        // END FORM PROJ



        return $this->render('societe/show.html.twig', [
            'societe' => $societe,
            'logEntries' => $qb->getResult(),
            'formSte' => $formSte,
            'formAdresse' => $formAdresse,
            'formContact' => $formContact,
            'formProj' => $formProj,
        ]);
    }








    #[Route('/{id}/edit', name: 'app_societe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteForm::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }



        return $this->redirectToRoute('app_societe_show', ['id' => $societe->getId()], Response::HTTP_SEE_OTHER);
    }








    #[Route('/{id}', name: 'app_societe_delete', methods: ['POST'])]
    public function delete(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($societe);
            $entityManager->flush();
        }



        return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
    }
}