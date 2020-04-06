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
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        $em = $this->getDoctrine()->getManager();
        $repo_fromage = $em->getRepository(Fromage::class);
        $repo_type = $em->getRepository(Type::class);
        $repo_lait = $em->getRepository(Lait::class);

        if ($request->isMethod('GET')) {
            $fromages = $repo_fromage->findAll();

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

        if ($request->isMethod('POST')) {
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            $origine = $body['origine'] ?? null;
            if ($origine == null || $origine == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'origine est invalide."
                ]);
            }

            $id_type = $body['type'] ?? null;
            if ($id_type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le type est manquant."
                ]);
            }
            $type = $repo_type->find($id_type);
            if ($type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le type est introuvable."
                ]);
            }

            $id_lait = $body['lait'] ?? null;
            if ($id_lait == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le lait est manquant."
                ]);
            }
            $lait = $repo_lait->find($id_lait);
            if ($lait == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le lait est introuvable."
                ]);
            }

            $prix = $body['prix'] ?? null;
            if ($prix == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le prix est invalide."
                ]);
            }

            $img = $body['img'] ?? null;

            $fromage = new Fromage();
            $fromage->setNom($nom);
            $fromage->setOrigine($origine);
            $fromage->setLait($lait);
            $fromage->setType($type);
            $fromage->setPrix($prix);
            $fromage->setImg($img);

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

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/fromage/{id}", name="api-fromage")
     */
    public function fromage(Request $request, $id) {
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        $em = $this->getDoctrine()->getManager();
        $repo_fromage = $em->getRepository(Fromage::class);
        $repo_type = $em->getRepository(Type::class);
        $repo_lait = $em->getRepository(Lait::class);

        $fromage = $repo_fromage->find($id);
        if ($fromage == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Des identifiants n'ont pas pu etre trouves"
            ]);
        }

        if ($request->isMethod('PUT')) {
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            $origine = $body['origine'] ?? null;
            if ($origine == null || $origine == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'origine est invalide."
                ]);
            }

            $id_type = $body['type'] ?? null;
            if ($id_type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le type est manquant."
                ]);
            }
            $type = $repo_type->find($id_type);
            if ($type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le type est introuvable."
                ]);
            }

            $id_lait = $body['lait'] ?? null;
            if ($id_lait == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le lait est manquant."
                ]);
            }
            $lait = $repo_lait->find($id_lait);
            if ($lait == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le lait est introuvable."
                ]);
            }

            $prix = $body['prix'] ?? null;
            if ($prix == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le prix est invalide."
                ]);
            }

            $img = $body['img'] ?? null;

            $fromage->setNom($nom);
            $fromage->setOrigine($origine);
            $fromage->setLait($lait);
            $fromage->setType($type);
            $fromage->setPrix($prix);
            $fromage->setImg($img);

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
