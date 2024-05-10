<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    /*
        1 ère étape du tunnel d'achat
        Choix de l'adresse de livraison et du transporteur 

    */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {

        $adresses = $this->getUser()->getAdresses();

        if(count($adresses) == 0){

            return $this->redirectToRoute('app_account_adress_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'adresses' => $adresses,
            // attention !on fait circuler le formulaire ,  on redirige l'utilisateur vers le récapitulatif lorqu'on valide
            'action' => $this->generateUrl('app_order_summary')

        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }


    /*
        2éme étape du tunnel d'achat
        Récap de la commande de l'utilisateur
        Insertion en BDD
        Préparation du paiement vers stripe
    */
    // Route ne doit être accessible que par la méthode POST, le formulaire précedent doit être impérativement soumis
    // #[Route('/commande/recapitulatif', name: 'app_order_summary', methods:['POST'])]

    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request , Cart $cart, EntityManagerInterface $entityManager): Response
    {

        //si la méthode n'est pas POST tu me rediriges vers une route 
        if( $request->getMethod() != 'POST'){
            return $this->redirectToRoute('app_cart');
        }


        $products = $cart->getCart();

        // on a besoin du formulaire précédent(adresse de livraison, transporteur) , pour cela on le remap
        $form = $this->createForm(OrderType::class, null, [
            'adresses' => $this->getUser()->getAdresses()
          

        ]);

        $form->handleRequest($request);
        
        if( $form->isSubmitted() && $form->isValid()){
        
            // dd($form->getData());
            //on va stocker les informations en BDD

            //Création de la chaîne adresse
            $adressObj = $form->get('adresses')->getData();

            $adress = $adressObj->getFirstname().' '.$adress = $adressObj->getLastname().'<br/>';
            $adress .= $adressObj->getAdress().'<br/>';
            $adress .= $adressObj->getPostal().' '.$adressObj->getCity().'<br/>';
            $adress .= $adressObj->getCountry().'<br/>';
            $adress .= $adressObj->getPhone();

            // dd($cart);



            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($adress);


            foreach($products as $product){

                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                //on lie OrderDetail a Order
                //Entité Order doit avoir les permissions pour créer les entités OrderDetails (cascade: persist)
                //voir classe Order 
                $order->addOrderDetail($orderDetail);
            }



        }

        $entityManager->persist($order);
        $entityManager->flush();


        return $this->render('order/summary.html.twig', [
            'choices' =>  $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotalWt()
        ]);
    }
}
