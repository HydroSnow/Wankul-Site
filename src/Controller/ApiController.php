<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Fromage;
use App\Entity\Type;
use App\Entity\Lait;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/fromage", name="api-fromages")
     */
    public function fromages(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Fromage::class);

        if ($request->isMethod('GET')) {
            $fromages = $repo->findAll();

            $list = [];
            foreach ($fromages as $fromage) {
                $list[] = [
                    'id' => $fromage->getId(),
                    'nom' => $fromage->getNom(),
                    'origine' => $fromage->getOrigine(),
                    'lait' => $fromage->getLait()->getId(),
                    'type' => $fromage->getType()->getId(),
                    'img' => $fromage->getImg(),
                    'prix' => $fromage->getPrix()
                ];
            }

            return new JsonResponse([
                "valid" => true,
                "result" => $list
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/fromage/{id}", name="api-fromage")
     */
    public function fromage(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Fromage::class);

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $origine = $request->request->get('origine');
            $id_lait = $request->request->get('lait');
            $id_type = $request->request->get('type');
            $prix = $request->request->get('prix');
            if ($nom == null || $origine == null || $id_lait == null || $id_type == null || $prix == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Il manque des arguments"
                ]);
            }

            $lait = $repo->find($id_lait);
            $type = $repo->find($id_type);
            if ($lait == null || $type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Des identifiants n'ont pas pu etre trouves"
                ]);
            }

            $fromage = new Fromage();
            $fromage->setNom($nom);
            $fromage->setOrigine($origine);
            $fromage->setLait($lait);
            $fromage->setType($type);
            $fromage->setPrix($prix);

            $em->persist($fromage);
            $em->flush();

            return new JsonResponse([
                "valid" => true,
                "result" => [
                    'id' => $fromage->getId(),
                    'nom' => $fromage->getNom(),
                    'origine' => $fromage->getOrigine(),
                    'lait' => $fromage->getLait()->getId(),
                    'type' => $fromage->getType()->getId(),
                    'img' => $fromage->getImg(),
                    'prix' => $fromage->getPrix()
                ]
            ]);
        }

        if ($request->isMethod('DELETE')) {
            $fromage = $repo->find($id);
            if ($fromage == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Des identifiants n'ont pas pu etre trouves"
                ]);
            }

            $em->remove($fromage);
            $em->flush();

            return new JsonResponse([
                "valid" => true
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/type", name="api-types")
     */
    public function types(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Type::class);

        if ($request->isMethod('GET')) {
            $types = $repo->findAll();

            $list = [];
            foreach ($types as $type) {
                $list[] = [
                    'id' => $type->getId(),
                    'nom' => $type->getNom()
                ];
            }

            return new JsonResponse([
                "valid" => true,
                "result" => $list
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/type/{id}", name="api-type")
     */
    public function type(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Type::class);

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/lait", name="api-laits")
     */
    public function laits(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Lait::class);

        if ($request->isMethod('GET')) {
            $laits = $repo->findAll();

            $list = [];
            foreach ($laits as $lait) {
                $list[] = [
                    'id' => $lait->getId(),
                    'nom' => $lait->getNom()
                ];
            }

            return new JsonResponse([
                "valid" => true,
                "result" => $list
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/lait/{id}", name="api-lait")
     */
    public function lait(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Lait::class);

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

}
