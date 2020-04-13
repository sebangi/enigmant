<?php

namespace App\Controller\Admin;

use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Form\Chene\JeuEnCheneType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin/chene/jeu-en-chene")
 */
class AdminJeuEnCheneController extends AbstractController {

    /**
     * @var JeuEnCheneRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(JeuEnCheneRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="admin_chene_jeuEnChene_index")  
     * @return Response
     */
    public function index(): Response {
        $jeuxEnChene = $this->repository->findAll();

        return $this->render('admin/chene/jeuEnChene/index.html.twig', [
                    'jeux_en_chene' => $jeuxEnChene,
                    'menu_courant' => 'Chêne']);
    }

    /**
     * @route("/create", name="admin_chene_jeuEnChene_new")  
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
            $this->addFlash('success', 'Bien créé avec succès.');
            return $this->redirectToRoute('admin_chene_jeuEnChene_index');
        }

        return $this->render('admin/chene/jeuEnChene/new.html.twig', [
                    'jeu_en_chene' => $jeuEnChene,
                    'form' => $form->createView(),
                    'menu_courant' => 'Chêne']);
    }

    /**
     * @route("/{id}/edit", name="admin_chene_jeuEnChene_edit", methods="GET|POST")  
     * @param JeuEnChene $jeuEnChene
     * @param Request $requete
     * @return Response
     */
    public function edit(JeuEnChene $jeuEnChene, Request $requete): Response {
        $form = $this->createForm(JeuEnCheneType::class, $jeuEnChene);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Bien modifé avec succès.');
            return $this->redirectToRoute('admin_chene_jeuEnChene_index');
        }

        return $this->render('admin/chene/jeuEnChene/edit.html.twig', [
                    'jeu_en_chene' => $jeuEnChene,
                    'form' => $form->createView(),
                    'menu_courant' => 'Chêne']);
    }

    /**
     * @route("/{id}", name="admin_chene_jeuEnChene_delete", methods="DELETE")  
     * @param JeuEnChene $jeuEnChene
     * @param Request $requete
     * @return Response
     */
        public function delete(JeuEnChene $jeuEnChene, Request $requete) {
        if ( $this->isCsrfTokenValid( 'delete' . $jeuEnChene->getId(), $requete->get('_token') ) )
        {
            $this->em->remove($jeuEnChene);
            $this->em->flush();
            
            $this->addFlash('success', 'Bien supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_chene_jeuEnChene_index');
    }

}
