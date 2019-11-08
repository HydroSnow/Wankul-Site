<?php
// src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
     * @Route("/login/", name="login")
     */
    public function login(Environment $twig, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $content = $twig->render('login.html.twig', [
            'username' => $lastUsername,
            'error' => $error
        ]);
        return new Response($content);
    }

    /**
     * @Route("/logout/", name="logout")
     */
    public function logout(Environment $twig, AuthenticationUtils $authenticationUtils)
    {
        return new Response("yo");
    }
}
