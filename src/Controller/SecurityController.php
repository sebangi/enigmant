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
use App\Form\General\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\General\Conversation;
use App\Entity\General\Message;

class SecurityController extends BaseController {

    protected function getThemeCourant(): ?string {
        return "General";
    }

    protected function getMenuCourant(): ?string {
        return null;
    }

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em);
    }

    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response {
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
     * @Route("/profil/{id}", name="profil")
     * @return Response
     */
    public function profil(Request $request, User $user): Response {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($user->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));
        }


        $form = $this->createForm(UserType::class, $user, ['preferences' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Vos préférences ont été mises à jour.');
            $this->em->flush();
            return $this->redirectToRoute('profil', [
                        "id" => $user->getId()]);
        }

        return $this->monRender('security/profil.html.twig', [
                    'form' => $form->createView(),
                    'user' => $this->getUser(),
        ]);
    }

    private function creerConversation(User $user) {
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        $conversation = new Conversation();
        $conversation->setUser($user);
        $conversation->setSujet("Bienvenue");
        $conversation->setCreeParGourou(true);

        $this->em->persist($conversation);

        $message1 = $this->creerMessage($conversation);
        $message1->setVuGourou(false);
        $message1->setTexte("Bonjour " . $user->getUsername() . ", \n\n"
                . "Bienvenue sur le site d'Énigmant !\n"
                . "Si vous avez des questions, des remarques, des suggestions, n'hésitez pas à écrire ci-dessous votre message.\n\n"
                . "À bientôt...");
        $this->em->persist($message1);
    }

    /**
     * @Route("/profil/edit/{id}", name="profil.edit")
     * @return Response
     */
    public function profilEdit(Request $request, User $user): Response {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($user->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));
        }


        $form = $this->createForm(UserType::class, $user, ['general' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('cancel')->isClicked()) {
                $this->addFlash('success', 'Vos informations générales ont été mises à jour.');
                $this->em->flush();
            }

            return $this->redirectToRoute('profil', [
                        "id" => $user->getId()]);
        }

        return $this->monRender('security/edit.html.twig', [
                    'form' => $form->createView(),
                    'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/mdp/edit/{id}", name="mdp.edit")
     * @return Response
     */
    public function mdpEdit(Request $request, User $user): Response {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            if ($user->getId() != $this->getUser()->getId()) {
                return $this->redirect($this->generateUrl('home'));
            }
        } else
            return $this->redirect($this->generateUrl('home'));

        $form = $this->createForm(UserType::class, $user, ['preferences' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Vos préférences ont été mises à jour.');
            $this->em->flush();
            return $this->redirectToRoute('profil', [
                        "id" => $user->getId()]);
        }

        return $this->monRender('security/profil.html.twig', [
                    'form' => $form->createView(),
                    'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/register", name="registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {
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
            $this->creerConversation($user);

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
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
