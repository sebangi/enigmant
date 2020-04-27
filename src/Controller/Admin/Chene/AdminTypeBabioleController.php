<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\TypeBabiole;
use App\Form\Chene\TypeBabioleType;
use App\Repository\Chene\TypeBabioleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/type-babiole")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminTypeBabioleController extends BaseController {

    /**
     * @var TypeBabioleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminTypeBabiole";
    }

    public function __construct(TypeBabioleRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="admin.chene.typeBabiole.index")  
     * @return Response
     */
    public function index(): Response {
        $typeBabioles = $this->repository->findAllByNum();

        return $this->monRender('admin/chene/typeBabiole/index.html.twig', [
                    'typeBabioles' => $typeBabioles
        ]);
    }

    /**
     * @route("/new", name="admin.chene.typeBabiole.new")  
     * @param Request $requete
     * @return Response
     */
    public function new(Request $requete) {
        $typeBabiole = new TypeBabiole();
        $form = $this->createForm(TypeBabioleType::class, $typeBabiole);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($typeBabiole); // Pour l'ajouter
            $this->em->flush();
            $this->addFlash('success', 'Type de babiole créé avec succès.');
            return $this->redirectToRoute('admin.chene.typeBabiole.index');
        }

        return $this->monRender('admin/chene/typeBabiole/new.html.twig', [
                    'typeBabiole' => $typeBabiole,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/{id}/edit", name="admin.chene.typeBabiole.edit", methods="GET|POST")  
     * @param TypeBabiole $typeBabiole
     * @param Request $requete
     * @return Response
     */
    public function edit(TypeBabiole $typeBabiole, Request $requete): Response {
        $form = $this->createForm(TypeBabioleType::class, $typeBabiole);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Type de babiole modifé avec succès.');
            return $this->redirectToRoute('admin.chene.typeBabiole.index');
        }

        return $this->monRender('admin/chene/typeBabiole/edit.html.twig', [
                    'typeBabiole' => $typeBabiole,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/{id}", name="admin.chene.typeBabiole.delete", methods="DELETE")  
     * @param TypeBabiole $typeBabiole
     * @param Request $requete
     * @return Response
     */
    public function delete(TypeBabiole $typeBabiole, Request $requete) {
        if ($this->isCsrfTokenValid('delete' . $typeBabiole->getId(), $requete->get('_token'))) {
            $this->em->remove($typeBabiole);
            $this->em->flush();

            $this->addFlash('success', 'Type de babiole supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.chene.typeBabiole.index');
    }

}
