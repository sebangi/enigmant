<?php

namespace App\Controller\Admin\General;

use App\Entity\General\Theme;
use App\Form\General\ThemeType;
use App\Repository\General\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/theme")
 */
class AdminThemeController extends AbstractController
{    
    /**
     * @var string
     */
    private $menuCourant = "Thème";
    
    /**
     * @var string
     */
    private $themeCourant = "Général";
    
    
    /**
     * @Route("/", name="admin.theme.index", methods={"GET"})
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('admin/general/theme/index.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'themes' => $themeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.theme.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();
            $this->addFlash('success', 'Thème ajouté avec succès.');
            
            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->render('admin/general/theme/new.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.theme.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Theme $theme): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Thème créé avec succès.');
            
            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->render('admin/general/theme/edit.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.theme.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Theme $theme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($theme);
            $entityManager->flush();
            $this->addFlash('success', 'Thême supprimé avec succès.');            
        }

        return $this->redirectToRoute('admin.theme.index');
    }
}
