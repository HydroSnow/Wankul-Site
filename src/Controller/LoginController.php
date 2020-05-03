<?php
// src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegistrationFormType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use App\Entity\Utilisateur;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
     * @Route("/login/", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout/", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('index');
    }

     /**
     * @Route("/registration/", name="registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Utilisateur();

        if (isset($_POST['password']) && isset($_POST['username'])) {
            // encode the password
            $user->setMdp(
                $passwordEncoder->encodePassword(
                    $user,
                    $_POST['password']
                )
            );
            $user->setNom($_POST['username']);

            $em = $this->getDoctrine()->getManager();
            $role = $em->getRepository(Role::class)->find(3);
            $user->setRole($role);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('registration.html.twig');
    }
}
