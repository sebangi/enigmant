<?php

namespace App\Controller\Admin\General;

use App\Entity\General\Theme;
use App\Form\General\ThemeType;
use App\Repository\General\ThemeRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/theme")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminThemeController extends BaseController
{    
    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminTheme";
    }
    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * @Route("/", name="admin.theme.index", methods={"GET"})
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->monRender('admin/general/theme/index.html.twig', [
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
            $this->em->persist($theme);
            $this->em->flush();
            $this->addFlash('success', 'Thème ajouté avec succès.');
            
            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->monRender('admin/general/theme/new.html.twig', [
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
            $this->em->flush();
            $this->addFlash('success', 'Thème créé avec succès.');
            
            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->monRender('admin/general/theme/edit.html.twig', [
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
            $this->em->remove($theme);
            $this->em->flush();
            $this->addFlash('success', 'Thême supprimé avec succès.');            
        }

        return $this->redirectToRoute('admin.theme.index');
    }
}
