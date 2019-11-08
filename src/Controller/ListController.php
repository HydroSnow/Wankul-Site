<?php
// src/Controller/ListController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Fromage;
use App\Entity\Lait;

class ListController extends AbstractController
{
   /**
    * @Route("/list/", name="list-all")
    */
    public function all(Environment $twig)
    {
        $em = $this->getDoctrine()->getManager();
        $laits = $em->getRepository(Lait::class)->findAll();
        $fromages = $em->getRepository(Fromage::class)->findAll();
        
        $content = $twig->render('list.html.twig', [
            'laits' => $laits,
            'fromages' => $fromages,
            'selected' => 'all'
        ]);
        return new Response($content);
    }

   /**
    * @Route("/list/{id}", name="list-category")
    */
    public function category(Environment $twig, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $laitRepo = $em->getRepository(Lait::class);
        $laits = $laitRepo->findAll();
        $lait = $laitRepo->find($id);
        $fromages = $lait->getFromages();

        $content = $twig->render('list.html.twig', [
            'laits' => $laits,
            'fromages' => $fromages,
            'selected' => $lait
        ]);
        return new Response($content);
    }
}
