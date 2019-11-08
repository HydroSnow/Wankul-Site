<?php
// src/Controller/ItemController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Fromage;
use App\Entity\Avis;

class ItemController extends AbstractController
{
    /**
     * @Route("/item/{id}", name="item")
     */
    public function item(Environment $twig, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fromage = $em->getRepository(Fromage::class)->find($id);
        $avis = $fromage->getAvis();

        $content = $twig->render('item.html.twig', [
            'fromage' => $fromage,
            'avis' => $avis
        ]);
        return new Response($content);
    }
}
