<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\CollectionChene;
use App\Form\Chene\CollectionCheneType;
use App\Repository\Chene\CollectionCheneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/collection")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCollectionCheneController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menuCourant = "AdminCollectionEnChene";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
    
    /**
     * @var CollectionCheneRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(CollectionCheneRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    
    /**
     * @Route("/", name="admin.chene.collectionChene.index", methods={"GET"})
     */
    public function index(CollectionCheneRepository $collectionCheneRepository): Response
    {
        return $this->render('admin/chene/collectionChene/index.html.twig', [
                    'collectionChenes' => $collectionCheneRepository->findBy(array(), array('num'=>'asc')),
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
            ]);
    }

    /**
     * @Route("/new", name="admin.chene.collectionChene.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $collectionChene = new CollectionChene();
        $form = $this->createForm(CollectionCheneType::class, $collectionChene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($collectionChene);
            $this->em->flush();
            $this->addFlash('success', 'Collection modifée avec succès.');

            return $this->redirectToRoute('admin.chene.collectionChene.index');
        }

        return $this->render('admin/chene/collectionChene/new.html.twig', [
                    'collection_chene' => $collectionChene,
                    'form' => $form->createView(),
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
        ]);
    }

    /**
     * @Route("/{id}", name="admin.chene.collectionChene.show", methods={"GET"})
     */
    public function show(CollectionChene $collectionChene): Response
    {
        return $this->render('admin/chene/collectionChene/show.html.twig', [
                    'collection_chene' => $collectionChene,
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.chene.collectionChene.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CollectionChene $collectionChene): Response
    {
        $form = $this->createForm(CollectionCheneType::class, $collectionChene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Collection modifée avec succès.');

            return $this->redirectToRoute('admin.chene.collectionChene.index');
        }

        return $this->render('admin/chene/collectionChene/edit.html.twig', [
            'collection_chene' => $collectionChene,
            'form' => $form->createView(),
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant
        ]);
    }

    /**
     * @Route("/{id}", name="admin.chene.collectionChene.delete", methods={"DELETE"})
     */
    public function delete(Request $request, CollectionChene $collectionChene): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectionChene->getId(), $request->request->get('_token'))) {
            $this->em->remove($collectionChene);
            $this->em->flush();
            $this->addFlash('success', 'Collection supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.chene.collectionChene.index');
    }
}
