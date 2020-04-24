<?php

namespace App\Controller\Admin\General;

use App\Entity\General\Niveau;
use App\Form\General\NiveauType;
use App\Repository\General\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/niveau")
 */
class AdminNiveauController extends AbstractController
{    
    /**
     * @var string
     */
    private $menuCourant = "AdminNiveau";
    
    /**
     * @var string
     */
    private $niveauCourant = "Général";
    
    
    /**
     * @Route("/", name="admin.niveau.index", methods={"GET"})
     */
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->render('admin/general/niveau/index.html.twig', [
            'menuCourant' => $this->menuCourant,
            'niveauCourant' => $this->niveauCourant,
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($niveau);
            $entityManager->flush();
            $this->addFlash('success', 'Niveau ajouté avec succès.');
            
            return $this->redirectToRoute('admin.niveau.index');
        }

        return $this->render('admin/general/niveau/new.html.twig', [
            'menuCourant' => $this->menuCourant,
            'niveauCourant' => $this->niveauCourant,
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
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Niveau créé avec succès.');
            
            return $this->redirectToRoute('admin.niveau.index');
        }

        return $this->render('admin/general/niveau/edit.html.twig', [
            'menuCourant' => $this->menuCourant,
            'niveauCourant' => $this->niveauCourant,
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($niveau);
            $entityManager->flush();
            $this->addFlash('success', 'Niveau supprimé avec succès.');            
        }

        return $this->redirectToRoute('admin.niveau.index');
    }
}
