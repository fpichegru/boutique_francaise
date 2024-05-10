<?php

namespace App\Controller\Account;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
     
    // Pour rendre le code plus lisible et maintenable, 
    // 1/ Solution : on pourrait créer d'autre (sous) controller   
    // AccountController
    // AccountAdressController
    // AccountOrderController
    // 2/ Solution : Créér un dossier account avec tous les controllers en rapport avec account:
    // Home Controller 
    // Adress Controller
    // OrderController
    // PasswordController  

    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }
}
