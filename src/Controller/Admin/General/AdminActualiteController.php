<?php

namespace App\Controller\Admin\General;

use App\Entity\General\Actualite;
use App\Form\General\ActualiteType;
use App\Repository\General\ActualiteRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/actualite")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminActualiteController extends BaseController
{    
    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminActualite";
    }    
    
    /**
     * @Route("/", name="admin.actualite.index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository): Response
    {
        return $this->monRender('admin/general/actualite/index.html.twig', [
            'actualites' => $actualiteRepository->findAll([],["date"=>'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="admin.actualite.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();
            $this->addFlash('success', 'Actualité ajoutée avec succès.');
            
            return $this->redirectToRoute('admin.actualite.index');
        }

        return $this->monRender('admin/general/actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.actualite.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actualite $actualite): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Actualité créée avec succès.');
            
            return $this->redirectToRoute('admin.actualite.index');
        }

        return $this->monRender('admin/general/actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.actualite.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actualite $actualite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actualite);
            $entityManager->flush();
            $this->addFlash('success', 'Actualité supprimée avec succès.');            
        }

        return $this->redirectToRoute('admin.actualite.index');
    }
}
