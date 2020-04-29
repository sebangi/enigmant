<?php

namespace App\Controller\Admin\General;

use App\Entity\General\User;
use App\Form\General\UserType;
use App\Form\General\UserPasswordType;
use App\Repository\General\UserRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\General\Niveau;
use App\Entity\General\ObtentionNiveau;
use App\Repository\General\NiveauRepository;

/**
 * @Route("/admin/user", name="admin.user.")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminUserController extends BaseController
{    
    /**
     *
     * @var UserRepository 
     */
    private $userRepository;
    
    /**
     *
     * @var EntityManagerInterface 
     */
    private $em;

    
    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminUser";
    }
    
    /**
     * 
     * @param UserRepository $repository
     * @param \App\Controller\Admin\General\EntityManagerInterface $em
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $em) {
        $this->userRepository = $repository;
        $this->em = $em;
    }
    
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(UserRepository $userRepository): Response
    {
        return $this->monRender('admin/general/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->monRender('admin/general/user/index.html.twig', [
            'users' => $userRepository->findAll(),
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
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['create' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            
            $this->em->persist($user);
            $this->ajouterPremierBadge($user);
            
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur ajouté avec succès.');
            
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->monRender('admin/general/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès.');
            
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->monRender('admin/general/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');            
        }

        return $this->redirectToRoute('admin.user.index');
    }
        
    /**
     * @Route("/password/change/{id}", name="password.change")
     */
    public function changePassword(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        
        $form = $this->createForm(UserPasswordType::class, $user, ['require_current_password' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$passwordEncoder->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                
                $this->addFlash('danger', 'Mot de passe incorrect.');
                return $this->redirectToRoute('admin.password.change', ['id' => $user]);
            }
            
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Le mot de passe a été modifié avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->monRender('admin/general/user/password.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
        
    }
    
    /**
     * @Route("/password/reset/{id}", name="password.reset")
     */
    public function resetPassword(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Le mot de passe a été modifié avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->monRender('admin/general/user/password.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }
    
}
