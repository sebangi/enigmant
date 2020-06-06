<?php

namespace App\Controller\Admin\General;

use App\Entity\General\Niveau;
use App\Form\General\NiveauType;
use App\Repository\General\NiveauRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/niveau")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminNiveauController extends BaseController
{    
    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminNiveau";
    }    
    
    /**
     * @Route("/", name="admin.niveau.index", methods={"GET"})
     */
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->monRender('admin/general/niveau/index.html.twig', [
            'niveaux' => $niveauRepository->findAllByTheme(),
        ]);
    }

    /**
     * @Route("/new", name="admin.niveau.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->$em->persist($niveau);
            $this->$em->flush();
            $this->addFlash('success', 'Niveau ajouté avec succès.');
            
            return $this->redirectToRoute('admin.niveau.index');
        }

        return $this->monRender('admin/general/niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.niveau.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Niveau $niveau): Response
    {
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->$em->flush();
            $this->addFlash('success', 'Niveau créé avec succès.');
            
            return $this->redirectToRoute('admin.niveau.index');
        }

        return $this->monRender('admin/general/niveau/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.niveau.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Niveau $niveau): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $this->$em->remove($niveau);
            $this->$em->flush();
            $this->addFlash('success', 'Niveau supprimé avec succès.');            
        }

        return $this->redirectToRoute('admin.niveau.index');
    }
}
