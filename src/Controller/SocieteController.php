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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/societe')]
final class SocieteController extends AbstractController
{

    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_societe_index', methods: ['GET'])]
    public function apiIndex(SocieteRepository $societeRepository, SerializerInterface $serializer): JsonResponse
    {
        $societes = $societeRepository->findAll();
        $jsonSocietes = $serializer->serialize($societes, 'json');



        return new JsonResponse($jsonSocietes, Response::HTTP_OK, [], true);
    }

            // -show
    #[Route('_api/{id}',name: 'api_societe_show', methods: ['GET'])]
    public function apiShow(Societe $societe, SerializerInterface $serializer): JsonResponse
    {
        $jsonSociete = $serializer->serialize($societe, 'json');



        return new JsonResponse($jsonSociete, Response::HTTP_OK, [], true);
    }

            // -new
    #[Route('_api/new', name: 'api_societe_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteForm::class, $societe);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societe);
            $entityManager->flush();
        }


        return new Response('', Response::HTTP_CREATED);
    }

            // -edit
    #[Route('_api/{id}', name: 'api_societe_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteForm::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }



        return new Response('', Response::HTTP_ACCEPTED);
    }

            // -delete
    #[Route('_api/{id}', name: 'api_societe_delete', methods: ['DELETE'])]
    public function apiDelete(Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($societe);
        $entityManager->flush();



        return new Response('', Response::HTTP_NO_CONTENT);
    }




    //// routes vues
            // -index
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
                        WHERE l.objectClass = 'App\Entity\Adresse'
                ");

                $adrIds = [];
                foreach($qb->getResult() as $adr){
                    if(isset($adr["data"]["societe"]["id"])){
                        if($adr["data"]["societe"]["id"] == $id){
                            $adrIds[] = $adr["objectId"];
                        }
                    }
                }
                $adrIds = array_unique($adrIds);

            //associated contacts selection
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\Contact'
                ");

                $ctcIds = [];
                foreach($qb->getResult() as $ctc){
                    if(isset($ctc["data"]["societe"]["id"])){
                        if($ctc["data"]["societe"]["id"] == $id){
                            $ctcIds[] = $ctc["objectId"];
                        }
                    }
                }
                $ctcIds = array_unique($ctcIds);

            //associated project selection
                $qb = $em->createQuery(
                    "SELECT l.objectId, l.data FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE l.objectClass = 'App\Entity\RelationProjetSociete'
                ");

                $prjIds = [];
                foreach($qb->getResult() as $prj){
                    if(isset($prj["data"]["societe"]["id"])){
                        if($prj["data"]["societe"]["id"] == $id){
                            $prjIds[] = $prj["objectId"];
                        }
                    }
                }
                $prjIds = array_unique($prjIds);


            //return query
                $qb = $em->createQuery(
                    "SELECT l FROM Gedmo\Loggable\Entity\LogEntry l 
                        WHERE 
                            ( l.objectClass = 'App\Entity\Societe'
                                AND l.objectId = (:steId)) 
                            OR ( l.objectClass = 'App\Entity\Adresse'
                                AND l.objectId in (:adrIds))
                            OR ( l.objectClass = 'App\Entity\Contact'
                                AND l.objectId in (:ctcIds))
                            OR ( l.objectClass = 'App\Entity\RelationProjetSociete'
                                AND l.objectId in (:prjIds))
                        ORDER BY l.loggedAt DESC
                ");
                $qb->setParameters([
                    'steId' => $id,
                    'adrIds' => $adrIds,
                    'ctcIds' => $ctcIds,
                    'prjIds' => $prjIds,
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