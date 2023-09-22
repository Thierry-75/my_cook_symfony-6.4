<?php

namespace App\Controller\Admin;

use App\Controller\MainController;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
      
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyCook.fr - Administration')
            ->renderContentMaximized()
            ->setLocales([
                'en' => '🇬🇧 English',
                'fr' => 'Fr Français'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Menu principal', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user-circle', User::class);
        yield MenuItem::linkToCrud('Contacts', 'fa fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Recettes', 'fa fa-spoon', Recette::class);
        yield MenuItem::linkToCrud('Ingrédients', 'fa fa-glass', Ingredient::class);
        yield MenuItem::linkToUrl('Quitter', 'fa fa-times', '/');
    }
}
