<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * function update user
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function index(User $user, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_security');
        }
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_main');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod("POST")) {
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render("pages/user/edit.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'le compte de : ' . $user->getFullName() . ' a été modifié !');
                    return $this->redirectToRoute('app_main');
                } else {
                    $this->addFlash('warning', 'Le mot de passe est incorrect');
                }
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()

        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'user_edit_password', methods: (['GET', 'POST']))]
    #[IsGranted('ROLE_USER')]
    /**
     * function update password
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    public function editPassword(User $user, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_security');
        }
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_main');
        }

        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);
        if ($request->isMethod("POST")) {
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render("pages/user/edit_password.html.twig", [
                    'form' => $form->createView(), 'errors' => $errors
                ]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                
                if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                    $user->setPassword(
                        $hasher->hashPassword(
                            $user,
                            $form->getData()->getNewPassword()
                        )
                    );
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Mot de passe de : ' . $user->getFullName() . ' a été modifié !');
                    return $this->redirectToRoute('app_security');
                } else {
                    $this->addFlash('warning', 'Le mot de passe est incorrect');
                }
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
