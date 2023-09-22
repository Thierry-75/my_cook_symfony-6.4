<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
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
            ->setEntityLabelInPlural('Ingrédientss')
            ->setEntityLabelInSingular('Ingrédient')
            ->setPageTitle('index', 'Administration des ingrédients')
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(['name'])
            ->setPaginatorPageSize(15)
            ->renderContentMaximized()
            ->setAutofocusSearch();
            
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name','Nom'),
            MoneyField::new('price','Prix')->setCurrency('EUR'),
            DateField::new('createAt','Date')
            ->hideOnIndex(),
            TextField::new('user.fullName','Ajouté par :')
        ];
    }
    
}
