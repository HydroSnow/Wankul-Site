<?php
// src/Controller/CartController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Fromage;
use App\Entity\Utilisateur;
use App\Entity\Commande;
use App\Entity\Detailcommande;
use App\Entity\Avis;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/", name="cart")
     */
     public function cart(Environment $twig)
     {
       $result = $this->getFromageSession();
       if($result == null) {
          $content = $twig->render('cart.html.twig', [
            'empty' => true
          ]);
       } else {
         $content = $twig->render('cart.html.twig', [
             'fromageList' => $result,
             'empty' => false
         ]);
       }
       return new Response($content);
     }

     public function addToCart(Environment $twig, $id) {
       // Adding product to a session array 'cart'
       $quantity = $_POST["_qt"];
       $cartTemp;
       $duplicate = false;
       if($this->get('session')->get('cart')) {
         $cartTemp = $this->get('session')->get('cart');
         foreach ($cartTemp as $key1 => $value1) {
           if($id == $value1['id']) {
             $duplicate = true;
           }
         }
         if(!$duplicate) {
            $cartTemp[] = array ('id' => $id, 'quantity' => $quantity);
            $this->get('session')->set('cart', $cartTemp);
          }
       }
       else {
         $cartTemp[] = array ('id' => $id, 'quantity' => $quantity);
         $this->get('session')->set('cart', $cartTemp);
       }

       //Reload of the item page
       return $this->redirectToRoute('item', array('id' => $id));
     }

     public function removeToCart(Environment $twig, $id) {
        $idFromageToDel;
        $result = $this->get('session')->get('cart');
        foreach ($result as $key => $value) {
          foreach ($value as $key2 => $value2) {
            if($key2 == 'id' && $value2 == $id)
              $idFromageToDel = $key;
          }
        }
        unset($result[$idFromageToDel]);
        $this->get('session')->set('cart', $result);
        return $this->redirectToRoute('cart');
     }

     public function command(Environment $twig) {
       $this->get('session')->set('paid', true);
       $content = $twig->render('command.html.twig', []);
       return new Response($content);
     }

     public function commandFinal(Environment $twig)
     {
       $result = $this->getFromageSession();

       if($result == null) {
         $content = $twig->render('cart.html.twig', [
           'empty' => true
         ]);
       } else {
         if($this->get('session')->get('paid') == true) {
           $currentUser = $this->getUser();
           $currentDate = new \DateTime();
           $command = new Commande();
           $command->setIdUtilisateur($currentUser);
           $command->setDate($currentDate);
           $em = $this->getDoctrine()->getManager();
           $em->persist($command);
           $em->flush();
           foreach ($result as $value) {
             $detailcommande = new Detailcommande();
             $detailcommande->setIdCommande($command);
             $detailcommande->setIdFromage($value['id']);
             $detailcommande->setQuantite($value['quantity']);
             $em->persist($detailcommande);
             $em->flush();
           }
           $this->get('session')->set('cart', null);
           $this->get('session')->set('paid', null);
           $content = $twig->render('commandFinal.html.twig', [
               'paid' => true,
               'fromageList' => $result
           ]);

         } else {
           $content = $twig->render('commandFinal.html.twig', [
               'paid' => false
           ]);
         }
       }
       return new Response($content);
     }


     private function getFromageSession() {
       if(!$this->get('session')->get('cart')) {
         return null;
       }

       $cartList = $this->get('session')->get('cart');
       $cartListTemp;
       $fromageList;
       $em = $this->getDoctrine()->getManager();
       foreach ($cartList as $key => $value) {
         foreach ($value as $key2 => $value2) {
           if($key2 == 'id') {
             $idvalue = $value2;
             $cartList[$key][$key2] = $em->getRepository(Fromage::class)->find($idvalue);
           }
         }

       }
       $fromageList = array_combine(array_keys($cartList), array_values($cartList));
       return $fromageList;
     }
}
