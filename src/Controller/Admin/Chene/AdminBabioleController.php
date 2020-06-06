<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\Babiole;
use App\Form\Chene\BabioleType;
use App\Repository\Chene\BabioleRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/babiole")
 * @IsGranted("ROLE_ADMIN")
  */
class AdminBabioleController extends BaseController
{
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminBabiole";
    }
    
    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * @Route("/", name="admin.chene.babiole.index", methods={"GET"})
     */
    public function index(BabioleRepository $babioleRepository): Response
    {
        return $this->monRender('admin/chene/babiole/index.html.twig', [
            'babioles' => $babioleRepository->findAllOrderTypeCategory(),
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
            $this->em->persist($babiole);
            $this->em->flush();
            $this->addFlash('success', 'Babiole ajoutée avec succès.');
            
            return $this->redirectToRoute('admin.chene.babiole.index');
        }

        return $this->monRender('admin/chene/babiole/new.html.twig', [
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
            $this->em->flush();
            $this->addFlash('success', 'Babiole créée avec succès.');
            
            return $this->redirectToRoute('admin.chene.babiole.index');
        }

        return $this->monRender('admin/chene/babiole/edit.html.twig', [
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
            $this->em->remove($babiole);
            $this->em->flush();
            $this->addFlash('success', 'Babiole supprimée avec succès.');            
        }

        return $this->redirectToRoute('admin.chene.babiole.index');
    }
}
