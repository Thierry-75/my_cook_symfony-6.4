<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index','Administration des utilisateurs')
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(['email', 'fullName'])
            ->setPaginatorPageSize(10);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','Id')
                ->hideOnForm(),
            TextField::new('fullName','Civilité :'),
            TextField::new('email','Email')
                ->setFormTypeOption('disabled','disabled'),
            TextField::new('pseudo','Pseudo'),
            DateField::new('createAt','Date d\'enregistrement')
                ->hideOnForm()
                ->setFormTypeOption('disabled','disabled'),
            ArrayField::new('roles','Droits')
                ->hideOnIndex()
            
        ];
    }
    
}
