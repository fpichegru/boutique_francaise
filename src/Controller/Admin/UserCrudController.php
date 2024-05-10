<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setEntityLabelInSingular('Utilisateur')
        ->setEntityLabelInPlural('Utilisateurs');
    ;
    }

    
    public function configureFields(string $pageName): iterable
    {

        //on ne doit pas permettre de supprimer l'email ou le mot de passe
        // on peut modifier le nom et le prénom

        return [
        
            TextField::new('firstname')->setLabel('Prénom'),
            TextField::new('lastname')->setLabel(('Nom')),
            TextField::new('email')->setLabel(('Email'))->onlyOnIndex(),
            
        ];
    }

}
