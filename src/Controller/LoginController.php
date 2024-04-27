<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        // Gérer les erreurs 
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier username (email) entré par l'utilisateur, si le 'utilisateur se trompe de mot de passe , évite d'avoir à retaper le login
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    #[Route('/deconnexion', name: 'app_logout', methods:['GET'])]
    public function logout(): never
    {

        throw new Exception( "Don't forget to activate logout in security.yaml");
    }
}
