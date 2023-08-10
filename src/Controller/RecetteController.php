<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette', methods:['GET'])]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10 
        );

        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    #[route('/recette/nouveau', 'recette_new', methods:['GET', 'POST' ])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator) :Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class,$recette);
        $form->handleRequest($request);
        if($request->isMethod("POST")){
            $errors = $validator->validate($recette);
            if(count($errors) > 0){
                return $this->render("pages/recette/new.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($recette);
                $entityManager->flush();
                $this->addFlash('success','la recette : '. $recette->getName().' a été enregistrée !');
                return $this->redirectToRoute('app_recette');
               
            }
        }
        return $this->render('/pages/recette/new.html.twig',['form' => $form->createView()]);
    }
}
