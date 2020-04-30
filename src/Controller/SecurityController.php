<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\General\User;
use App\Form\General\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\General\Niveau;
use App\Entity\General\ObtentionNiveau;
use App\Repository\General\NiveauRepository;

class SecurityController extends BaseController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    
    protected function getThemeCourant() : ?string
    {
        return "General";
    }
    
    protected function getMenuCourant() : ?string
    {
        return null;
    }
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }        
    
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) : Response {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->redirectToRoute('home');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->monRender('security/login.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }
    
    public function ajouterPremierBadge(User $user) 
    {
        $niveauRepository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveaux = $niveauRepository->findBy(array("num" => "1"));
        
        foreach ($niveaux as $niveau) {
            $opt = new ObtentionNiveau();
            $opt->setVu(false);
            $opt->setNiveau($niveau);
            $opt->setUser($user);
            $opt->setDate(new \DateTime('now'));
            
            $this->em->persist($opt);
        }        
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
            $this->ajouterPremierBadge($user);
            
            $this->em->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('login');
        }

        return $this->monRender('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}