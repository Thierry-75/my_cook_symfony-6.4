<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
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
            ->setEntityLabelInPlural('Contacts')
            ->setEntityLabelInSingular('Contact')
            ->setPageTitle('index', 'Administration des contacts')
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(['email', 'fullName'])
            ->setPaginatorPageSize(15)
            ->renderContentMaximized()
            ->setAutofocusSearch()
            ->setDefaultSort(['createAt' => 'DESC']);
            
            
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Id')
                ->setColumns(1)
                ->hideOnForm(),
            DateField::new('createAt', 'Date de réception')
                ->setColumns(4)
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('fullName', 'Civilité :')
            ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email', 'Email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('subject', 'Sujet')
            ->setFormTypeOption('disabled', 'disabled'),
            TextEditorField::new('message','Message')
                ->setNumOfRows(20)
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
