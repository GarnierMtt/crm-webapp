<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use App\Utils\ApiQueryBuilder;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
final class ContactController extends AbstractController
{
    //// api documentation
    #[Route('_api_docs',name: 'api_contact_documentation', methods: ['GET'])]
    public function documentation(EntityManagerInterface $em, Request $request): Response
    {
        $mappings = array();

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getFieldNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getTypeOfField($atribute)];
        }

        $atributes = $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getAssociationNames();
        foreach($atributes as $atribute){
            $mappings[] = [$atribute, $em->getMetadataFactory()->getMetadataFor('App\\Entity\\Contact')->getAssociationTargetClass($atribute)];
        }


        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);



        return $this->render('api/api_obj_index.html.twig', [
            'class' => "contact",
            'atributes' => $mappings,
            'form' => $form,
        ]);
    }


    // api return
    private function apiReturn($response): Response
    {
        // response
        if($_SERVER["HTTP_ACCEPT"] == "text/html"){
            $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
            return $this->render('api/api_obj_response.html.twig', [
                'data' => $response,
            ]);
        }
        return $response;
    }


    //// routes pour l'api
            // -index
    #[Route('_api',name: 'api_contact_index', methods: ['GET'])]
    public function apiIndex(ContactRepository $contactRepository, Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        // base query
        $qb = $contactRepository->createQueryBuilder('contact');
        $qb->leftJoin('contact.societe', 'societe')
           ->addSelect('societe')
           ->leftJoin('contact.adresses', 'adresses')
           ->addSelect('adresses')
           ->leftJoin('adresses.adresse', 'adresse')
           ->addSelect('adresse');

        
        return $this->apiReturn($apiQueryBuilder->returnIndex($qb, $request, "contact"));
    }


            // -show
    #[Route('_api/{id}',name: 'api_contact_show', methods: ['GET'])]
    public function apiShow(Contact $contact, ApiQueryBuilder $apiQueryBuilder): Response
    {


        return $this->apiReturn($apiQueryBuilder->returnShow($contact));
    }


            // -new
    #[Route('_api/new', name: 'api_contact_new', methods: ['POST'])]
    public function apiNew(Request $request, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);


        return $this->apiReturn($apiQueryBuilder->returnNew($contact, $form));
    }


            // -edit
    #[Route('_api/{id}', name: 'api_contact_edit', methods: ['POST'])]
    public function apiEdit(Request $request, Contact $contact, ApiQueryBuilder $apiQueryBuilder): Response
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);


        return $this->apiReturn($apiQueryBuilder->returnEdit($form));
    }


            // -delete
    #[Route('_api/{id}', name: 'api_contact_delete', methods: ['DELETE'])]
    public function apiDelete(Contact $contact, ApiQueryBuilder $apiQueryBuilder): Response
    {
       

        return $this->apiReturn($apiQueryBuilder->returnDelete($contact));
    }




    //// routes vues
            // -index
    #[Route(name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', []);
    }

            // -show
    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('contact/show.html.twig', ['id' => $id]);
    }
}
