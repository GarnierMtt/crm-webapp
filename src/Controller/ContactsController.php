<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\ContactsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/contacts')]
final class ContactsController extends AbstractController
{
    //// api documentation
    #[Route('_docs',name: 'api_contacts_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contacts')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contacts')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contacts')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contacts')->getAssociationTargetClass($atribute)];
        }


        $contacts = new Contacts();
        $form = $this->createForm(ContactsForm::class, $contacts);
        $form->handleRequest($request);



        return $this->render('api/obj_index.html.twig', [
            'class' => "contacts",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('',name: 'api_contacts_index', methods: ['GET'])]
    public function apiIndex(ContactsRepository $contactsRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {



        return $apiQueryBuilder->returnIndex($contactsRepository, $request, "contacts");
    }


            // -show
    #[Route('/{id}',name: 'api_contacts_show', methods: ['GET'])]
    public function apiShow(Contacts $contacts, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($contacts, $request);
    }


            // -new
    #[Route('/new', name: 'api_contacts_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $contacts = new Contacts();
        $form = $this->createForm(ContactsForm::class, $contacts);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($contacts, $form);
    }


            // -edit
    #[Route('/{id}', name: 'api_contacts_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contacts $contacts, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ContactsForm::class, $contacts);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('/{id}', name: 'api_contacts_delete', methods: ['DELETE'])]
    public function apiDelete(Contacts $contacts, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($contacts);
    }
}
