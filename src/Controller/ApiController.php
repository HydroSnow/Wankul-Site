<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Lait;
use App\Entity\Fromage;
use App\Entity\Type;

class ApiController extends AbstractController
{
    private function fromage($action, $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Fromage::class);

        if ($action == "list") {
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

        if ($action == "get") {
            $id = $request->request->get('id');
            if ($id == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Il manque des arguments"
                ]);
            }

            $fromage = $repo->find($id);
            if ($fromage == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Des identifiants n'ont pas pu etre trouves"
                ]);
            }

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

        if ($action == "add") {
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

        if ($action == "remove") {
            $id = $request->request->get('id');
            if ($id == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Il manque des arguments"
                ]);
            }

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
            "error" => "Action inconnue"
        ]);
    }

    private function type($action, $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Type::class);

        if ($action == "list") {
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

        if ($action == "get") {
            $id = $request->request->get('id');
            if ($id == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Il manque des arguments"
                ]);
            }

            $type = $repo->find($id);
            if ($type == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Des identifiants n'ont pas pu etre trouves"
                ]);
            }

            return new JsonResponse([
                "valid" => true,
                "result" => [
                    'id' => $type->getId(),
                    'nom' => $type->getNom()
                ]
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "Action inconnue"
        ]);
    }

    public function lait($action, $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Lait::class);

        if ($action == "list") {
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

        if ($action == "get") {
            $id = $request->request->get('id');
            if ($id == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Il manque des arguments"
                ]);
            }

            $lait = $repo->find($id);
            if ($lait == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Des identifiants n'ont pas pu etre trouves"
                ]);
            }

            return new JsonResponse([
                "valid" => true,
                "result" => [
                    'id' => $lait->getId(),
                    'nom' => $lait->getNom()
                ]
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "Action inconnue"
        ]);
    }

    /**
     * @Route("/api/", name="api")
     */
    public function api(Request $request) {
        if (!$request->isMethod('POST')) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Les requetes doivent etre faites avec la methode POST"
            ]);
        }

        if ($request->query->get('token') != "992bbd2dfa42f3078025dd8ee76dd16b") {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le jeton d'authentification est invalide ou manquant"
            ]);
        }

        $entity = $request->query->get('entity');
        $action = $request->query->get('action');
        if ($entity == null  || $action == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Les requetes doivent contenir une entite et une action"
            ]);
        }

        if ($entity == "fromage") {
            return $this->fromage($action, $request);

        } else if ($entity == "type") {
            return $this->type($action, $request);

        } else if ($entity == "lait") {
            return $this->lait($action, $request);

        } else {
            return new JsonResponse([
                "valid" => false,
                "error" => "Entite inconnue"
            ]);
        }

    }

}
