<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{    
    /**
     * @var string
     */
    private $menuCourant = "User";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
    
    /**
     * @Route("/", name="admin.user.home", methods={"GET"})
     */
    public function home(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'users' => $userRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/", name="admin.user.index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.user.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur ajoutée avec succès.');
            
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.user.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Utilisateur créée avec succès.');
            
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.user.delete", methods={"DELETE"})
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
}
