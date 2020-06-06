<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Form\Chene\JeuEnCheneType;
use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/jeu-en-chene")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminJeuEnCheneController extends BaseController {

    /**
     * @var JeuEnCheneRepository
     */
    private $repository;
    
    
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminJeuEnChene";
    }

    public function __construct(JeuEnCheneRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @route("/", name="admin.chene.jeuEnChene.index")  
     * @return Response
     */
    public function index(): Response {
        $jeuxEnChene = $this->repository->findAllBOrderByCollection();

        return $this->monRender('admin/chene/jeuEnChene/index.html.twig', [
                    'jeuxEnChene' => $jeuxEnChene
        ]);
    }

    /**
     * @route("/new", name="admin.chene.jeuEnChene.new")  
     * @param Request $requete
     * @return Response
     */
    public function new(Request $requete) {
        $jeuEnChene = new JeuEnChene();
        $form = $this->createForm(JeuEnCheneType::class, $jeuEnChene);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($jeuEnChene); // Pour l'ajouter
            $this->em->flush();
            $this->addFlash('success', 'Jeu en chêne créé avec succès.');
            return $this->redirectToRoute('admin.chene.jeuEnChene.index');
        }

        return $this->monRender('admin/chene/jeuEnChene/new.html.twig', [
                    'jeuEnChene' => $jeuEnChene,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/{id}/edit", name="admin.chene.jeuEnChene.edit", methods="GET|POST")  
     * @param JeuEnChene $jeuEnChene
     * @param Request $requete
     * @return Response
     */
    public function edit(JeuEnChene $jeuEnChene, Request $requete): Response {
        $form = $this->createForm(JeuEnCheneType::class, $jeuEnChene);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Jeu en chêne modifé avec succès.');
            return $this->redirectToRoute('admin.chene.jeuEnChene.index');
        }

        return $this->monRender('admin/chene/jeuEnChene/edit.html.twig', [
                    'jeuEnChene' => $jeuEnChene,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/{id}", name="admin.chene.jeuEnChene.delete", methods="DELETE")  
     * @param JeuEnChene $jeuEnChene
     * @param Request $requete
     * @return Response
     */
    public function delete(JeuEnChene $jeuEnChene, Request $requete) {
        if ($this->isCsrfTokenValid('delete' . $jeuEnChene->getId(), $requete->get('_token'))) {
            $this->em->remove($jeuEnChene);
            $this->em->flush();

            $this->addFlash('success', 'Jeu en chêne supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.chene.jeuEnChene.index');
    }

}
