<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\General\User;
use App\Form\General\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends BaseController
{
    
    protected function getThemeCourant() : ?string
    {
        return "General";
    }
    
    protected function getMenuCourant() : ?string
    {
        return null;
    }
    
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) : Response {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->redirectToRoute('home');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->monRender('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }
            
    /**
     * @Route("/register", name="registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->em->persist($user);            
            $this->ajouterPremierGrade($user);
            
            $this->em->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->monRender('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}