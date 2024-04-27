<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }


    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager ): Response
    {

    

       //permet de récupérer le user courant 
        $user= $this->getUser();
       
        
        // je n'oublie pas de passer mon Hasher en paramètre
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            //on met à jour la BDD avec le nouveau mot de passe
            // pas besoin de persist car , il n'y a pas création d'un nouvel objet, c'est une MAJ

            $this->addFlash(
            'success',
            'Votre mot de passe a bien été mis à jour!'
        );

            $entityManager->flush();
          
        }

        return $this->render('account/password.html.twig',[
           'modifyPwd' => $form->createView()
        ]);
    }
}
