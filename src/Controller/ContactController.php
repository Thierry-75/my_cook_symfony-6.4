<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods:['GET','POST'])]
    public function index(Request $request,EntityManagerInterface $em, ValidatorInterface $validator, MailService $mailService ): Response
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
                    //email pages/contact/signup.html.twig
                    //   'expiration_date'=> new \DateTime('+7 days'),'contact' => $contact,
                    $mailService->sendMail($contact->getEmail(),$contact->getSubject(),
                    'pages/contact/signup.html.twig',['expiration_date'=>new \DateTime('+7 days'),'contact'=>$contact]);
                    $this->addFlash('success', 'Votre demande a bien été enrregistrée !');
                    return $this->redirectToRoute('app_main');
                }
        }
        return $this->render('pages/contact/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
