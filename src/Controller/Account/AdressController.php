<?php

namespace App\Controller\Account;

use App\Classes\Cart;
use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;


class AdressController extends AbstractController{


    
    private $entityManager;

    // on déclare un entityManager pour l'ensemble de la classe , pour éviter les répétitions
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }


    #[Route('/compte/adresses/delete/{id}', name: 'app_account_adress_delete')]
    public function delete($id,  AdressRepository $adressRepository): Response
    {

        $adress = $adressRepository->findOneById($id);

        if(!$adress || $adress->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_adresses');
        }

        $this->addFlash(
            'success',
            'Votre adresse a bien été supprimée!'
        );

        // on supprime l'adresse dans la BDD
        $this->entityManager->remove($adress);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_adresses');
    }


     #[Route('/compte/adresses', name: 'app_account_adresses')]
    public function index(): Response
    {
        return $this->render('account/adress/index.html.twig');
    }








    //on va utiliser cette route pour ajouter et modifier une adresse
    // si je mets un Id en param cela signifie une modification sur cette adresse a
    // si il n y a pas de d'Id passe en param , cela signifie qu'il s'agit d'un ajout d'adresse 
    // attention par défaut $id doit être à NULL
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_adress_form', defaults:['id' => null])]
    public function form(Request $request, $id, AdressRepository $adressRepository, Cart $cart): Response
    {

        if($id){

            $adress = $adressRepository->findOneById($id);
            //pb de sécurité car l Id de l'adresse circule en clair dans l'URL
            // bien vérifier que l'adresse appartient bien à notre utilisateur et qu'elle existe 
            if(!$adress || $adress->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_account_adresses');
            }

        }else{

              $adress = new Adress();
              //je lie l'objet adress avec le user associé(celui de la session en cours)
              $adress->setUser($this->getUser()) ;

        }
        
      
        
        $form = $this->createForm( AdressUserType::class, $adress);
   

        $form->handleRequest($request);


          if( $form->isSubmitted() && $form->isValid()){
     

            $this->entityManager->persist($adress);
            $this->entityManager->flush();

            $this->addFlash(
            'success',
            'Votre adresse a bien été enregistrée!'
            );


            //si mon panier n'est pas vide , je renvoie sur le tunnel d'achat
            if($cart->fullQuantity() > 0 ){

                return $this->redirectToRoute("app_order");
            }

            return $this->redirectToRoute("app_account_adresses");

        }

        return $this->render('account/adress/form.html.twig', [
            'adressForm' => $form
        ]);
    }


}