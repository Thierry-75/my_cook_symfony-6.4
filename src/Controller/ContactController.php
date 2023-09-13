<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods:['GET','POST'])]
    public function index(Request $request,EntityManagerInterface $em, ValidatorInterface $validator ): Response
    {
        $contact = new Contact();
        if($this->getUser()){
            $contact->setFullName($this->getUser()->getFullName());
            $contact->setEmail($this->getUser()->getEmail());

        }
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($contact);
                if (count($errors) > 0) {
                    return $this->render("pages/contact/index.html.twig", [
                        'form' => $form->createView(), 'errors' => $errors
                    ]);
                }
                if($form->isSubmitted() && $form->isValid()){
                    $em->persist($contact);
                    $em->flush();
                    $this->addFlash('success', 'Votre demande a bien été enrregistrée !');
                    return $this->redirectToRoute('app_main');
                }
        }
        return $this->render('pages/contact/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
