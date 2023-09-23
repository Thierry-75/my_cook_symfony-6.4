<?php

namespace App\Controller\Admin;

use App\Entity\Recette;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class RecetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Recettes')
            ->setEntityLabelInSingular('Recette')
            ->setPageTitle('index','Administration des recettes')
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(['name'])
            ->setPaginatorPageSize(10);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','Id')
                ->hideOnForm(),
            TextField::new('name','Recette'),
            IntegerField::new('time','temps en mn'),
            IntegerField::new('nbPeople','Nb de personne'),
            IntegerField::new('difficulty','Difficulté'),
            TextEditorField::new('description','Description')
            ->hideOnIndex(),
            MoneyField::new('price','Prix')->setCurrency('EUR'),
            DateField::new('createAt','Date')
            ->hideOnIndex(),
            BooleanField::new('isPublic','Public')->hideOnForm(),
            CollectionField::new('ingredients','Ingrédients')->renderExpanded()
            ->hideOnIndex()->hideOnForm()
        ];
    }
    
}
