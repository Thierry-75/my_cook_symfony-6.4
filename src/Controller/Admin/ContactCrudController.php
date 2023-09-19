<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Contacts')
            ->setEntityLabelInSingular('Contact')
            ->setPageTitle('index', 'Administration des contacts')
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(['email', 'fullName'])
            ->setPaginatorPageSize(15);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Id')
                ->setColumns(1)
                ->hideOnForm(),
            DateField::new('createAt', 'Date de réception')
                ->setColumns(4)
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('fullName', 'Civilité :'),
            TextField::new('email', 'Email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('subject', 'Sujet'),
            TextEditorField::new('message','Message')
                ->setNumOfRows(20)
                ->hideOnIndex()
        ];
    }
}
