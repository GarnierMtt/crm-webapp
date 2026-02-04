<?php

namespace App\Controller;

use App\Form\LiensFibreForm;
use App\Entity\LiensFibre;
use App\Repository\LiensFibreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/lien/fibre')]
final class LiensFibreController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_liensFibre_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\LiensFibre')->getAssociationTargetClass($atribute)];
        }


        $liensfibre = new LiensFibre();
        $form = $this->createForm(LiensFibreForm::class, $liensfibre);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "liensFibre",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }








    #[Route(name: 'app_lien_fibre_index', methods: ['GET'])]
    public function index(LiensFibreRepository $liensFibreRepository): Response
    {



        return $this->render('lien_fibre/index.html.twig', [
            'lien_fibres' => $liensFibreRepository->findAll(),
        ]);
    }








    #[Route('/new', name: 'app_lien_fibre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $liensFibre = new LiensFibre();
        $form = $this->createForm(LiensFibreForm::class, $liensFibre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($liensFibre);
            $entityManager->flush();

            return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('lien_fibre/new.html.twig', [
            'lien_fibre' => $liensFibre,
            'form' => $form,
        ]);
    }








    #[Route('/{id}', name: 'app_lien_fibre_show', methods: ['GET'])]
    public function show(LiensFibre $liensFibre): Response
    {



        return $this->render('lien_fibre/show.html.twig', [
            'lien_fibre' => $liensFibre,
        ]);
    }








    #[Route('/{id}/edit', name: 'app_lien_fibre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LiensFibre $liensFibre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LiensFibreForm::class, $liensFibre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('lien_fibre/edit.html.twig', [
            'lien_fibre' => $liensFibre,
            'form' => $form,
        ]);
    }








    #[Route('/{id}', name: 'app_lien_fibre_delete', methods: ['POST'])]
    public function delete(Request $request, LiensFibre $liensFibre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liensFibre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($liensFibre);
            $entityManager->flush();
        }


        
        return $this->redirectToRoute('app_lien_fibre_index', [], Response::HTTP_SEE_OTHER);
    }
}
