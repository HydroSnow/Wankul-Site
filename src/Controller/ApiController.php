<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Utilisateur;
use App\Entity\Token;
use App\Entity\Fromage;
use App\Entity\Type;
use App\Entity\Lait;
use App\Entity\Role;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/login", name="api-login")
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        $em = $this->getDoctrine()->getManager();
        $repo_user = $em->getRepository(Utilisateur::class);
        $repo_token = $em->getRepository(Token::class);

        if ($request->isMethod('POST')) {
            // username verification
            $username = $body['username'] ?? null;
            if ($username == null || $username == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom d'utilisateur est invalide."
                ]);
            }

            // user retrieval
            $user = $repo_user->findOneBy([ "nom" => $username ]);
            if ($user == null) {
                return new JsonResponse([
                    "valid" => false, "error" => "Le nom d'utilisateur ne correspond à aucune entrée."
                ]);
            }

            // password verification
            $password = $body['password'] ?? null;
            if ($password == null || $password == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le mot de passe est invalide."
                ]);
            }

            // password checking
            if ($passwordEncoder->isPasswordValid($user, $password) == false) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le mot de passe est incorrect."
                ]);
            }

            // entity generation
            $token = new Token();

            $token->setUser($user);

            $validBefore = new \DateTime();
            $validBefore->modify('-10 minute');
            $token->setValidbefore($validBefore);

            $validAfter = new \DateTime();
            $validAfter->modify('+1 day');
            $token->setValidafter($validAfter);

            $em->persist($token);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => [
                    "id" => $token->getId(),
                    "valid_before" => $validBefore,
                    "valid_after" => $validAfter
                ]
            ]);
        }

        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/fromage", name="api-fromages")
     */
    public function fromages(Request $request) {
        // parameters
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        // entities repo
        $em = $this->getDoctrine()->getManager();
        $repo_fromage = $em->getRepository(Fromage::class);
        $repo_type = $em->getRepository(Type::class);
        $repo_lait = $em->getRepository(Lait::class);
        $repo_token = $em->getRepository(Token::class);

        // get (get fromage list)
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

        // post (post new fromage)
        if ($request->isMethod('POST')) {
            // token verification
            if ($request->headers->has('X-AUTH-TOKEN') == false) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est manquant."
                ]);
            }
            $token_id = $request->headers->get('X-AUTH-TOKEN');
            $token = $repo_token->find($token_id);
            if ($token == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est incorrect."
                ]);
            }
            if ($token->getUser()->getRole()->getId() != 1) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'utilisateur n'a pas les permissions."
                ]);
            }

            // parameters verification
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

            // entity creation
            $fromage = new Fromage();
            $fromage->setNom($nom);
            $fromage->setOrigine($origine);
            $fromage->setLait($lait);
            $fromage->setType($type);
            $fromage->setPrix($prix);
            $fromage->setImg($img);

            $em->persist($fromage);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $fromage->getId(),
                    'nom' => $fromage->getNom(),
                    'origine' => $fromage->getOrigine(),
                    'lait' => $fromage->getLait()->getId(),
                    'type' => $fromage->getType()->getId(),
                    'img' => $fromage->getImg(),
                    'prix' => $fromage->getPrix()
                ])
            ]);
        }

        // if method not known
        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/fromage/{id}", name="api-fromage")
     */
    public function fromage(Request $request, $id) {
        // parameters
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        // entities repo
        $em = $this->getDoctrine()->getManager();
        $repo_fromage = $em->getRepository(Fromage::class);
        $repo_type = $em->getRepository(Type::class);
        $repo_lait = $em->getRepository(Lait::class);
        $repo_token = $em->getRepository(Token::class);

        // find fromage
        $fromage = $repo_fromage->find($id);
        if ($fromage == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Des identifiants n'ont pas pu etre trouves"
            ]);
        }

        // token verification
        if ($request->headers->has('X-AUTH-TOKEN') == false) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est manquant."
            ]);
        }
        $token_id = $request->headers->get('X-AUTH-TOKEN');
        $token = $repo_token->find($token_id);
        if ($token == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est incorrect."
            ]);
        }
        if ($token->getUser()->getRole()->getId() != 1) {
            return new JsonResponse([
                "valid" => false,
                "error" => "L'utilisateur n'a pas les permissions."
            ]);
        }

        // UPDATE
        if ($request->isMethod('PUT')) {
            // parameters verification
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

            // entity set
            $fromage->setNom($nom);
            $fromage->setOrigine($origine);
            $fromage->setLait($lait);
            $fromage->setType($type);
            $fromage->setPrix($prix);
            $fromage->setImg($img);

            $em->persist($fromage);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $fromage->getId(),
                    'nom' => $fromage->getNom(),
                    'origine' => $fromage->getOrigine(),
                    'lait' => $fromage->getLait()->getId(),
                    'type' => $fromage->getType()->getId(),
                    'img' => $fromage->getImg(),
                    'prix' => $fromage->getPrix()
                ])
            ]);
        }

        // DELETE
        if ($request->isMethod('DELETE')) {
            // entity removal
            $em->remove($fromage);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true
            ]);
        }

        // unknown method
        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/type", name="api-types")
     */
    public function types(Request $request) {
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }
        // parameters
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Type::class);
        $repo_token = $em->getRepository(Token::class);

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

        if ($request->isMethod('POST')) {
              // token verification
              if ($request->headers->has('X-AUTH-TOKEN') == false) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est manquant."
                ]);
            }
            $token_id = $request->headers->get('X-AUTH-TOKEN');
            $token = $repo_token->find($token_id);
            if ($token == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est incorrect."
                ]);
            }
            if ($token->getUser()->getRole()->getId() != 1) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'utilisateur n'a pas les permissions."
                ]);
            }
            // parameters verification
            $name = $body['nom'] ?? null;
            if ($name == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est manquant."
                ]);
            }
            // entity creation
            $type = new Type();
            $type->setNom($name);

            $em->persist($type);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $type->getId(),
                    'nom' => $type->getNom()
                ])
            ]);

        }

        // if method not known
        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/type/{id}", name="api-type")
     */
    public function type(Request $request, $id) {    
        // parameters
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        // entities repo
        $em = $this->getDoctrine()->getManager();
        $repo_type = $em->getRepository(Type::class);
        $repo_token = $em->getRepository(Token::class);

        // find
        $types = $repo_type->find($id);
        if ($types == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le type $types n'a pas pu etre trouve"
            ]);
        }

        // token verification
        if ($request->headers->has('X-AUTH-TOKEN') == false) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est manquant."
            ]);
        }
        $token_id = $request->headers->get('X-AUTH-TOKEN');
        $token = $repo_token->find($token_id);
        if ($token == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est incorrect."
            ]);
        }
        if ($token->getUser()->getRole()->getId() != 1) {
            return new JsonResponse([
                "valid" => false,
                "error" => "L'utilisateur n'a pas les permissions."
            ]);
        }


        // UPDATE
        if ($request->isMethod('PUT')) {
            // parameters verification
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            // entity set
            $types->setNom($nom);

            $em->persist($types);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $types->getId(),
                    'nom' => $types->getNom()
                ])
            ]);
        }

        // DELETE
        if ($request->isMethod('DELETE')) {
            // entity removal
            $em->remove($types);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true
            ]);
        }
    }

    /**
     * @Route("/api/lait", name="api-laits")
     */
    public function laits(Request $request) {
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }
        // parameters
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Lait::class);
        $repo_token = $em->getRepository(Token::class);

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

        if ($request->isMethod('POST')) {
              // token verification
              if ($request->headers->has('X-AUTH-TOKEN') == false) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est manquant."
                ]);
            }
            $token_id = $request->headers->get('X-AUTH-TOKEN');
            $token = $repo_token->find($token_id);
            if ($token == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est incorrect."
                ]);
            }
            if ($token->getUser()->getRole()->getId() != 1) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'utilisateur n'a pas les permissions."
                ]);
            }
            // parameters verification
            $name = $body['nom'] ?? null;
            if ($name == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est manquant."
                ]);
            }
            // entity creation
            $lait = new Lait();
            $lait->setNom($name);

            $em->persist($lait);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $lait->getId(),
                    'nom' => $lait->getNom()
                ])
            ]);

        }

        // if method not known
        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/lait/{id}", name="api-lait")
     */
    public function lait(Request $request, $id) {
        
        // parameters
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        // entities repo
        $em = $this->getDoctrine()->getManager();
        $repo_lait = $em->getRepository(Lait::class);
        $repo_token = $em->getRepository(Token::class);

        // find
        $laits = $repo_lait->find($id);
        if ($laits == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le lait $laits n'a pas pu etre trouve"
            ]);
        }

        // token verification
        if ($request->headers->has('X-AUTH-TOKEN') == false) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est manquant."
            ]);
        }
        $token_id = $request->headers->get('X-AUTH-TOKEN');
        $token = $repo_token->find($token_id);
        if ($token == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est incorrect."
            ]);
        }
        if ($token->getUser()->getRole()->getId() != 1) {
            return new JsonResponse([
                "valid" => false,
                "error" => "L'utilisateur n'a pas les permissions."
            ]);
        }


        // UPDATE
        if ($request->isMethod('PUT')) {
            // parameters verification
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            // entity set
            $laits->setNom($nom);

            $em->persist($laits);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $laits->getId(),
                    'nom' => $laits->getNom()
                ])
            ]);
        }

        // DELETE
        if ($request->isMethod('DELETE')) {
            // entity removal
            $em->remove($laits);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true
            ]);
        }
    }

    /**
     * @Route("/api/utilisateur", name="api-utilisateurs")
     */
    public function utilisateurs(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }
        // parameters
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Utilisateur::class);
        $repo_token = $em->getRepository(Token::class);

        if ($request->isMethod('GET')) {
            $utilisateurs = $repo->findAll();

            $list = [];
            foreach ($utilisateurs as $utilisateur) {
                $list[] = [
                    'id' => $utilisateur->getId(),
                    'nom' => $utilisateur->getNom(),
                    'role' => $utilisateur->getRole()->getId(),
                    'mdp' => $utilisateur->getMdp()
                ];
            }

            return new JsonResponse([
                "valid" => true,
                "result" => $list
            ]);
        }

        if ($request->isMethod('POST')) {
              // token verification
              if ($request->headers->has('X-AUTH-TOKEN') == false) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est manquant."
                ]);
            }
            $token_id = $request->headers->get('X-AUTH-TOKEN');
            $token = $repo_token->find($token_id);
            if ($token == null) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le token est incorrect."
                ]);
            }
            if ($token->getUser()->getRole()->getId() != 1) {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "L'utilisateur n'a pas les permissions."
                ]);
            }
            // parameters verification
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            $role = $body['role'] ?? null;
            if ($role == null || $role == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le role est invalide."
                ]);
            }

            $mdp = $body['mdp'] ?? null;
            if ($mdp == null || $mdp == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le mot de passe est invalide."
                ]);
            }

            // entity set
            $utilisateurs = new Utilisateur();
            $utilisateurs->setNom($nom);
            $utilisateurs->setRole($em->getRepository(Role::class)->find($role));
            $utilisateurs->setMdp($mdp);

            $em->persist($utilisateurs);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $utilisateurs->getId(),
                    'nom' => $utilisateurs->getNom(),
                    'role' => $utilisateurs->getRole()->getId(),
                    'mdp' => $utilisateurs->getMdp()
                ])
            ]);

        }

        // if method not known
        return new JsonResponse([
            "valid" => false,
            "error" => "La requete est invalide."
        ]);
    }

    /**
     * @Route("/api/utilisateur/{id}", name="api-utilisateur")
     */
    public function utilisateur(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder) {
        // parameters
        $body = [];
        if ($content = $request->getContent()) {
            $body = json_decode($content, true);
        }

        // entities repo
        $em = $this->getDoctrine()->getManager();
        $repo_utilisateur = $em->getRepository(Utilisateur::class);
        $repo_token = $em->getRepository(Token::class);

        // find
        $utilisateurs = $repo_utilisateur->find($id);
        if ($utilisateurs == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "L'utilisateur $utilisateurs n'a pas pu etre trouve"
            ]);
        }

        // token verification
        if ($request->headers->has('X-AUTH-TOKEN') == false) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est manquant."
            ]);
        }
        $token_id = $request->headers->get('X-AUTH-TOKEN');
        $token = $repo_token->find($token_id);
        if ($token == null) {
            return new JsonResponse([
                "valid" => false,
                "error" => "Le token est incorrect."
            ]);
        }
        if ($token->getUser()->getRole()->getId() != 1) {
            return new JsonResponse([
                "valid" => false,
                "error" => "L'utilisateur n'a pas les permissions."
            ]);
        }


        // UPDATE
        if ($request->isMethod('PUT')) {
            // parameters verification
            $nom = $body['nom'] ?? null;
            if ($nom == null || $nom == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le nom est invalide."
                ]);
            }

            $role = $body['role'] ?? null;
            if ($role == null || $role == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le role est invalide."
                ]);
            }

            $mdp = $body['mdp'] ?? null;
            if ($mdp == null || $mdp == '') {
                return new JsonResponse([
                    "valid" => false,
                    "error" => "Le mot de passe est invalide."
                ]);
            }

            // entity set
            $utilisateurs->setNom($nom);
            $utilisateurs->setRole($em->getRepository(Role::class)->find($role));
            $utilisateurs->setMdp($mdp);

            $em->persist($utilisateurs);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true,
                "result" => array([
                    'id' => $utilisateurs->getId(),
                    'nom' => $utilisateurs->getNom(),
                    'role' => $utilisateurs->getRole()->getId(),
                    'mdp' => $utilisateurs->getMdp()
                ])
            ]);
        }

        // DELETE
        if ($request->isMethod('DELETE')) {
            // entity removal
            $em->remove($utilisateurs);
            $em->flush();

            // response
            return new JsonResponse([
                "valid" => true
            ]);
        }
    }
}
