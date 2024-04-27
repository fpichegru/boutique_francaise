<?php

// NAMESPACE => je définis un répertoire

// USE => je l'appelle 

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        //dd($resquest) ;

        
        $user = new User();
       
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);

        // si le formulaire est soumis alors tu enregistres les data en BDD
        // tu envoies un message de confirmation du compte bien créé
        if($form->isSubmitted() && $form->isValid()){

            // die("Formulaire soumis");
            // dd($form->getData());
            // dd($user);
      
            // fige les données , 
            $entityManager->persist($user);
            //enregistre les données
            $entityManager->flush();

            $this->addFlash(
            'success',
            'Votre compte est bien créé , veuillez vous connecter.'
            );

            //je renvois mon utilisateur sur la page de login 
            return $this->redirectToRoute('app_login'); 


        }

        return $this->render('register/index.html.twig',[
            "registerForm" => $form->createView()
        ]);
    }
}
