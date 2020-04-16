<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\Babiole;
use App\Form\Chene\BabioleType;
use App\Repository\Chene\BabioleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/chene/babiole")
 */
class AdminBabioleController extends AbstractController
{
    /**
     * @var string
     */
    private $menu_courant = "AdminBabiole";
    
    /**
     * @var string
     */
    private $theme_courant = "Chêne";
    
    /**
     * @Route("/", name="admin_chene_babiole_index", methods={"GET"})
     */
    public function index(BabioleRepository $babioleRepository): Response
    {
        return $this->render('admin/chene/babiole/index.html.twig', [
            'menu_courant' => 'AdminBabiole',
            'theme_courant' => 'Chêne',
            'babioles' => $babioleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_chene_babiole_new", methods={"GET","POST"})
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
            
            return $this->redirectToRoute('admin_chene_babiole_index');
        }

        return $this->render('admin/chene/babiole/new.html.twig', [
            'menu_courant' => 'AdminBabiole',
            'theme_courant' => 'Chêne',
            'babiole' => $babiole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_chene_babiole_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Babiole $babiole): Response
    {
        $form = $this->createForm(BabioleType::class, $babiole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Babiole créée avec succès.');
            
            return $this->redirectToRoute('admin_chene_babiole_index');
        }

        return $this->render('admin/chene/babiole/edit.html.twig', [
            'menu_courant' => 'AdminBabiole',
            'theme_courant' => 'Chêne',
            'babiole' => $babiole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_chene_babiole_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Babiole $babiole): Response
    {
        if ($this->isCsrfTokenValid('delete'.$babiole->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($babiole);
            $entityManager->flush();
            $this->addFlash('success', 'Babiole supprimée avec succès.');            
        }

        return $this->redirectToRoute('admin_chene_babiole_index');
    }
}