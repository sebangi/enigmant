<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\CategorieBabiole;
use App\Form\Chene\CategorieBabioleType;
use App\Repository\Chene\CategorieBabioleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/categorie-babiole")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCategorieBabioleController extends BaseController {

    /**
     * @var CategorieBabioleRepository
     */
    private $repository;

    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminCategorieBabiole";
    }
    
    public function __construct(EntityManagerInterface $em, CategorieBabioleRepository $repository) {
        parent::__construct($em);
        $this->repository = $repository;
    }

    /**
     * @route("/", name="admin.chene.categorieBabiole.index")  
     * @return Response
     */
    public function index(): Response {
        $categorieBabioles = $this->repository->findAllByNum();

        return $this->monRender('admin/chene/categorieBabiole/index.html.twig', [
                    'categorieBabioles' => $categorieBabioles,
        ]);
    }

    /**
     * @route("/new", name="admin.chene.categorieBabiole.new")  
     * @param Request $requete
     * @return Response
     */
    public function new(Request $requete) {
        $categorieBabiole = new CategorieBabiole();
        $form = $this->createForm(CategorieBabioleType::class, $categorieBabiole);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($categorieBabiole); // Pour l'ajouter
            $this->em->flush();
            $this->addFlash('success', 'Catégorie de babiole créée avec succès.');
            return $this->redirectToRoute('admin.chene.categorieBabiole.index');
        }

        return $this->monRender('admin/chene/categorieBabiole/new.html.twig', [
                    'categorieBabiole' => $categorieBabiole,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/{id}/edit", name="admin.chene.categorieBabiole.edit", methods="GET|POST")  
     * @param CategorieBabiole $categorieBabiole
     * @param Request $requete
     * @return Response
     */
    public function edit(CategorieBabiole $categorieBabiole, Request $requete): Response {
        $form = $this->createForm(CategorieBabioleType::class, $categorieBabiole);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Catégorie de babiole modifée avec succès.');
            return $this->redirectToRoute('admin.chene.categorieBabiole.index');
        }

        return $this->monRender('admin/chene/categorieBabiole/edit.html.twig', [
                    'categorieBabiole' => $categorieBabiole,
                    'form' => $form->createView()                    
        ]);
    }

    /**
     * @route("/{id}", name="admin.chene.categorieBabiole.delete", methods="DELETE")  
     * @param CategorieBabiole $categorieBabiole
     * @param Request $requete
     * @return Response
     */
    public function delete(CategorieBabiole $categorieBabiole, Request $requete) {
        if ($this->isCsrfTokenValid('delete' . $categorieBabiole->getId(), $requete->get('_token'))) {
            $this->em->remove($categorieBabiole);
            $this->em->flush();

            $this->addFlash('success', 'Catégorie de babiole supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.chene.categorieBabiole.index');
    }

}
