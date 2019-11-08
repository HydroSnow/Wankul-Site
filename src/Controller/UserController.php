<?php
// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Utilisateur;

class UserController extends AbstractController
{
   /**
    * @Route("/user/{id}", name="user")
    */
    public function user(Environment $twig, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $selected = $em->getRepository(Utilisateur::class)->find($id);

        $content = $twig->render('user.html.twig', [
            'selected' => $selected
        ]);
        return new Response($content);
    }
}
