<?php

namespace App\Controller\Admin\General;

use App\Entity\General\ObtentionNiveau;
use App\Form\General\ObtentionNiveauType;
use App\Repository\General\ObtentionNiveauRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/grade")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminObtentionNiveauController extends BaseController {

    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminObtentionNiveau";
    }

    /**
     * @Route("/", name="admin.obtentionNiveau.index", methods={"GET"})
     */
    public function index(ObtentionNiveauRepository $obtentionNiveauRepository): Response {
        return $this->monRender('admin/general/obtentionNiveau/index.html.twig', [
                    'obtentionNiveaux' => $obtentionNiveauRepository->findAllAvecJointure(),
        ]);
    }

    /**
     * @Route("/new", name="admin.obtentionNiveau.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $obtentionNiveau = new ObtentionNiveau();
        $obtentionNiveau->setDate(new \DateTime('now'));
        $form = $this->createForm(ObtentionNiveauType::class, $obtentionNiveau);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($obtentionNiveau->getUser()->hasGrade($obtentionNiveau->getNiveau())) {
                dump($obtentionNiveau->getDate());        
                $this->addFlash('error', 'Le joueur avait déjà ce grade.');
                return $this->redirectToRoute('admin.obtentionNiveau.index');
            }

            $entityManager->persist($obtentionNiveau);
            $entityManager->flush();
            $this->addFlash('success', 'Grade ajouté avec succès.');

            return $this->redirectToRoute('admin.obtentionNiveau.index');
        }

        return $this->monRender('admin/general/obtentionNiveau/new.html.twig', [
                    'obtentionNiveau' => $obtentionNiveau,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.obtentionNiveau.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ObtentionNiveau $obtentionNiveau): Response {
        $form = $this->createForm(ObtentionNiveauType::class, $obtentionNiveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Grade créé avec succès.');

            return $this->redirectToRoute('admin.obtentionNiveau.index');
        }

        return $this->monRender('admin/general/obtentionNiveau/edit.html.twig', [
                    'obtentionNiveau' => $obtentionNiveau,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.obtentionNiveau.delete", methods={"DELETE"})
     */
    public function delete(Request $request, ObtentionNiveau $obtentionNiveau): Response {
        if ($this->isCsrfTokenValid('delete' . $obtentionNiveau->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($obtentionNiveau);
            $entityManager->flush();
            $this->addFlash('success', 'Grade supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.obtentionNiveau.index');
    }

}
