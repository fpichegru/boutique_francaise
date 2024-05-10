<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        //1. j'ouvre une connexion avec ma base de données 
        //2. Connecte toi à la table qui s'appelle Category
        //3. Fais une action en BDD

        // un repository est une classe qui permet de faire des requête dans une table dédié
        // CategoryRepository nous permet de faire des requêtes sur la table Catégorie

        // findOne Categorie by Slug passé en paramètre 
        $category = $categoryRepository->findOneBySlug($slug);

        //si la categorie est introuvable on renvoie sur la page d'accueil
        if(!$category){
            return $this->redirectToRoute('app_home');
        }



        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
