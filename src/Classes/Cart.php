<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart{

    public function __construct(
        private RequestStack $requestStack
    )
    {
        
    }

         /*
    getCart()
    fonction retournant le panier 
    */
    public function getCart(){

        return  $this->requestStack->getSession()->get('cart');
    }

    /*
    add()
    fonction permettant l'ajout d'un produit au panier
    */

    public function add($product){


        // je vais appeler la session de symfony 
        // $session = $this->requestStack->getSession();
        // on récupère le panier en cours avec les produits précédents si il y en a 
       
        // $cart =  $this->requestStack->getSession()->get('cart');
        $cart =  $this->getCart();


      
        // Ajouter une qtity + 1  à mon produit

        // si mon produit est déjà dans mon panier 
        if (isset($cart[$product->getId()])){

            $cart[$product->getId()] = [
                
                'object'=> $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
                
            ];
            
        }else{

                $cart[$product->getId()] = [
                
                'object'=> $product,
                'qty' => 1
                
            ];
        }
          // créer ma session Cart, 2 param , le nom de la session ,  et 1 valeur  , ici un tableau(avec 2 champs , object , quantité)
          $this->requestStack->getSession()->set('cart', $cart);
        // dd( $this->requestStack->getSession()->get('cart'));

    }


      /*
    decrease()
    fonction permettant la suppression d'une quantité d'un produit au panier
    */
    public function decrease($id){

            // $cart =  $this->requestStack->getSession()->get('cart');

            $cart =  $this->getCart();

          
            // si la qté du produit est > à 1
            if ( $cart[$id]['qty'] > 1 ) 
            {

                $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
          
              
            
            }else{

               //sinon tu me supprimes mon entrée dans le tableau
               unset( $cart[$id]);
                
            }

            //je mets à jour la session avec les nouvelles valeurs 
            $this->requestStack->getSession()->set('cart', $cart);
        
    }

         /*
    fullQuantity()
    fonction permettant de retourner le nombre de produits total au panier
    */
    public function fullQuantity(){


        // $cart =  $this->requestStack->getSession()->get('cart');
        $cart =  $this->getCart();
        $quantity = 0;


        //si le panier est vide tu me retourne 0,
        if(!isset($cart)){

            return $quantity;
        }

        foreach( $cart as $product){

            $quantity = $quantity + $product['qty'];
        }

        return $quantity;
    }

         /*
    getTotalWt()
    fonction retournant le prix total TTC au panier 
    */

    public function getTotalWt(){


        // $cart =  $this->requestStack->getSession()->get('cart');
        $cart =  $this->getCart();
        $totalPrice = 0;

        
        // si le panier est vide on renvoie 0
        if(!isset($cart)){

            return $totalPrice;
        }


        foreach( $cart as $product){

            $totalPrice =$totalPrice + ($product['qty'] * $product['object']->getPriceWt());
        }

        return $totalPrice;

      
    }

             /*
    Remove()
    fonction permettant de vider totalement le panier 
    */

    public function remove()
    {
       return $this->requestStack->getSession()->remove('cart');
    }
}