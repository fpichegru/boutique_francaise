<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\PasswordUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


class PasswordController extends AbstractController{


    private $entityManager;

    // on déclare un entityManager pour l'ensemble de la classe , pour éviter les répétitions
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

     #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher ): Response
    {

    

       //permet de récupérer le user courant 
        $user= $this->getUser();
       
        
        // je n'oublie pas de passer mon Hasher en paramètre
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        // je veux que tu écoutes la requête pour allez plus loin
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            //on met à jour la BDD avec le nouveau mot de passe
            // pas besoin de persist car , il n'y a pas création d'un nouvel objet, c'est une MAJ

            $this->addFlash(
            'success',
            'Votre mot de passe a bien été mis à jour!'
        );

            $this->entityManager->flush();
          
        }

        return $this->render('account/password/index.html.twig',[
           'modifyPwd' => $form->createView()
        ]);
    }


}