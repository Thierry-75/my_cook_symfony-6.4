<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Form\MarkType;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\MarkRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    #[Route('/recette', name: 'app_recette', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }
    #[Route('/recettes/publics', name: 'all_recettes', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function recettes(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findByRecettesPublics(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('pages/recette/index_public.html.twig', ['recettes' => $recettes]);
    }



    /**
     * function create recette
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[route('/recette/nouveau', 'recette_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($request->isMethod("POST")) {
            $errors = $validator->validate($recette);
            if (count($errors) > 0) {
                return $this->render("pages/recette/new.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $recette->setUser($this->getUser());
                $entityManager->persist($recette);
                $entityManager->flush();
                $this->addFlash('success', 'la recette : ' . $recette->getName() . ' a été ajoutée !');
                return $this->redirectToRoute('app_recette');
            }
        }
        return $this->render('/pages/recette/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * function update recette
     *
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/recette/modification/{id}', name: 'recette_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Recette $recette, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user =  $this->getUser();  // renvoie l'id du connecté 
        $user_recette = $entityManager->getRepository(Recette::class)->find($recette); // renvoie l'id du proprietaire de l'article
        if ($user->id === ($user_recette->getUser()->getId())) {
            $form = $this->createForm(RecetteType::class, $recette);
            $form->handleRequest($request);
            if ($request->isMethod("POST")) {
                $errors = $validator->validate($recette);
                if (count($errors) > 0) {
                    return $this->render("pages/recette/edit.html.twig", [
                        'form' => $form->createView(), 'errors' => $errors
                    ]);
                }
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($recette);
                    $entityManager->flush();
                    $this->addFlash('success', 'l\'ingrédient : ' . $recette->getName() . ' a été modifié !');
                    return $this->redirectToRoute('app_recette');
                }
            }
            return $this->render('pages/recette/edit.html.twig', ['form' => $form->createView()]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * function delete recette
     *
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/recette/suppression/{id}', name: 'recette_delete', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Recette $recette, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $user =  $this->getUser();  // renvoie l'id du connecté
        $user_recette = $entityManager->getRepository(Recette::class)->find($recette); // renvoie l'id du proprietaire de l'article
        if ($user->id === ($user_recette->getUser()->getId())) {
            $form = $this->createForm(RecetteType::class, $recette);
            $form->handleRequest($request);
            if ($request->isMethod("POST")) {
                $errors = $validator->validate($recette);
                if (count($errors) > 0) {
                    return $this->render("pages/recette/delete.html.twig", [
                        'form' => $form->createView(), 'errors' => $errors
                    ]);
                }
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($recette);
                    $entityManager->flush();
                    $this->addFlash('success', 'La recette : ' . $recette->getName() . ' a été supprimé !');
                    return $this->redirectToRoute('app_recette');
                }
            }
            return $this->render('pages/recette/delete.html.twig', ['form' => $form->createView()]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }

    /**
     * function show recette public
     *
     * @param Recette $recette
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/recette/{id}', name: 'recette_show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function show(Recette $recette, EntityManagerInterface $entityManager, Request $request, MarkRepository $markRepository): Response
    {

        $recette = $entityManager->getRepository(Recette::class)->find($recette);
        if ($recette->isIsPublic()  || $recette->getUser() === $this->getUser()) {
            $mark = new Mark();
            $form = $this->createForm(MarkType::class, $mark);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mark->setUser($this->getUser())
                    ->setRecette($recette);
                $user = $this->getUser();
                $existingMark = $markRepository->findOneBy(['user' => $user->id, 'recette' => $recette]);
                if (!$existingMark) {
                    $entityManager->persist($mark);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre note a bien été prise en compte');
                } else {
                    $this->addFlash('warning', 'Vous avez déjà noté cette recette');
                }
                return $this->redirectToRoute('all_recettes');
            }

            return $this->render('pages/recette/show.html.twig', ['recette' => $recette, 'form' => $form->createView()]);
        } else {
            return $this->redirectToRoute('app_recette');
        }
    }
}
