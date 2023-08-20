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
    /**
     * function list recettes
     *
     * @param RecetteRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
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
    /**
     * function create recette
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
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
                $this->addFlash('success','la recette : '. $recette->getName().' a été ajoutée !');
                return $this->redirectToRoute('app_recette');
               
            }
        }
        return $this->render('/pages/recette/new.html.twig',['form' => $form->createView()]);
    }

    #[Route('/recette/modification/{id}', name:'recette_edit', methods:['GET', 'POST'])]
    /**
     * function update recette
     *
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function edit( Recette $recette, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator) :Response
    {
        $form = $this->createForm(RecetteType::class,$recette);
        $form->handleRequest($request);
        if($request->isMethod("POST")){
            $errors = $validator->validate($recette);
            if(count($errors) > 0){
                return $this->render("pages/recette/edit.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($recette);
                $entityManager->flush();
                $this->addFlash('success','l\'ingrédient : '.$recette->getName().' a été modifié !');
                return $this->redirectToRoute('app_recette');
               
            }
        }
        return $this->render('pages/recette/edit.html.twig',['form' => $form->createView()]);
    }

    #[Route('/recette/suppression/{id}', name:'recette_delete', methods:['GET', 'POST'])]
    /**
     * function delete recette
     *
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function delete(Recette $recette, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator) :Response
    {
        $form = $this->createForm(RecetteType::class,$recette);
        $form->handleRequest($request);
        if($request->isMethod("POST")){
            $errors = $validator->validate($recette);
            if(count($errors) > 0){
                return $this->render("pages/recette/delete.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($recette);
                $entityManager->flush();
                $this->addFlash('success','La recette : '.$recette->getName().' a été supprimé !');
                return $this->redirectToRoute('app_recette');
               
            }
        }
        return $this->render('pages/recette/delete.html.twig',['form' => $form->createView()]);
        
    }
}
