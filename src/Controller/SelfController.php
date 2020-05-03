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
    public function self()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        return $this->render('self.html.twig');
    }
}
