<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IngredientController extends AbstractController
{
    /**
     * function list ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10 
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    #[Route('/ingredient/nouveau', name:'ingredient_new', methods:['GET','POST'] )]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator) :Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);
        if($request->isMethod("POST")){
            $errors = $validator->validate($ingredient);
            if(count($errors) > 0){
                return $this->render("pages/ingredient/new.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($ingredient);
                $entityManager->flush();
                $this->addFlash('success','Votre ingrédient a été enregistré !');
                return $this->redirectToRoute('app_ingredient');
               
            }
        }
        return $this->render('pages/ingredient/new.html.twig',
    ['form' => $form->createView()]);
    }
}
