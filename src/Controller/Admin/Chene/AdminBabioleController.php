<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\Babiole;
use App\Form\Chene\BabioleType;
use App\Repository\Chene\BabioleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/babiole")
 * @IsGranted("ROLE_ADMIN")
  */
class AdminBabioleController extends AbstractController
{
    /**
     * @var string
     */
    private $menuCourant = "AdminBabiole";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
    
    /**
     * @Route("/", name="admin.chene.babiole.index", methods={"GET"})
     */
    public function index(BabioleRepository $babioleRepository): Response
    {
        return $this->render('admin/chene/babiole/index.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'babioles' => $babioleRepository->findAllByType(),
        ]);
    }

    /**
     * @Route("/new", name="admin.chene.babiole.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $babiole = new Babiole();
        $form = $this->createForm(BabioleType::class, $babiole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($babiole);
            $entityManager->flush();
            $this->addFlash('success', 'Babiole ajoutée avec succès.');
            
            return $this->redirectToRoute('admin.chene.babiole.index');
        }

        return $this->render('admin/chene/babiole/new.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'babiole' => $babiole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.chene.babiole.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Babiole $babiole): Response
    {
        $form = $this->createForm(BabioleType::class, $babiole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Babiole créée avec succès.');
            
            return $this->redirectToRoute('admin.chene.babiole.index');
        }

        return $this->render('admin/chene/babiole/edit.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'babiole' => $babiole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.chene.babiole.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Babiole $babiole): Response
    {
        if ($this->isCsrfTokenValid('delete'.$babiole->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($babiole);
            $entityManager->flush();
            $this->addFlash('success', 'Babiole supprimée avec succès.');            
        }

        return $this->redirectToRoute('admin.chene.babiole.index');
    }
}
