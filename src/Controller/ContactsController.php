<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\ContactsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contacts')]
final class ContactsController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_contacts_documentation', methods: ['GET'])]
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



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "contacts",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_contacts_index', methods: ['GET'])]
    public function apiIndex(ContactsRepository $contactsRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $contactsRepository->createQueryBuilder('contacts');
        $qb->leftJoin('contacts.fk_societes', 'societes')
           ->addSelect('societes')
           /*->leftJoin('contacts.adresses', 'adresses')
           ->addSelect('adresses')
           ->leftJoin('adresses.adresse', 'adresse')
           ->addSelect('adresse')//*/
           ;

        
        return $apiQueryBuilder->returnIndex($qb, $request, "contacts");
    }


            // -show
    #[Route('_api/{id}',name: 'api_contacts_show', methods: ['GET'])]
    public function apiShow(Contacts $contacts, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $apiQueryBuilder->returnShow($contacts);
    }


            // -new
    #[Route('_api/new', name: 'api_contacts_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $contacts = new Contacts();
        $form = $this->createForm(ContactsForm::class, $contacts);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnNew($contacts, $form);
    }


            // -edit
    #[Route('_api/{id}', name: 'api_contacts_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contacts $contacts, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ContactsForm::class, $contacts);
        $form->handleRequest($request);


        return $apiQueryBuilder->returnEdit($form);
    }


            // -delete
    #[Route('_api/{id}', name: 'api_contacts_delete', methods: ['DELETE'])]
    public function apiDelete(Contacts $contacts, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $apiQueryBuilder->returnDelete($contacts);
    }




    //// routes vues
            // -index
    #[Route(name: 'app_contacts_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contacts/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_contacts_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('contacts/show.html.twig', ['id' => $id]);
    }
}
