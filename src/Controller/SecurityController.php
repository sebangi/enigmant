<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) : Response {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->redirectToRoute('home.index');
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }
    
    /**
     * @Route("/registration", name="registration", methods={"GET", "POST"})
     * @return Response
     */
    public function registration(AuthenticationUtils $authenticationUtils) : Response {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->redirectToRoute('home.index');
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }
}