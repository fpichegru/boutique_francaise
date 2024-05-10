<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        // on peut récupérer le paramètre de l'url précèdente
        //dd($request->headers->get('referer'));
        $product = $productRepository->findOneById($id);
        $cart->add($product);

        $this->addFlash(
            'success',
            'Le produit a bien été ajouté au panier.'
        );

        // on redirige sur l'URL précedente, que l'on obtient grâce à l'injection request 
        return $this->redirect($request->headers->get('referer'));

    }


    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {

      
        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Le produit a bien été supprimé du panier.'
        );

        // on redirige sur l'URL précedente, que l'on obtient grâce à l'injection request 
        return $this->redirectToRoute("app_cart");

    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove( Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_home') ;
    }

}
