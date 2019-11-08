<?php
// src/Controller/SelfController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SelfController extends AbstractController
{
   /**
    * @Route("/self/", name="self")
    */
    public function self(Environment $twig)
    {
        $content = $twig->render('self.html.twig', []);
        return new Response($content);
    }
}
