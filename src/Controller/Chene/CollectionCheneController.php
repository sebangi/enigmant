<?php

namespace App\Controller\Chene;

use App\Entity\Chene\CollectionChene;
use App\Repository\Chene\CollectionCheneRepository;
use \App\Entity\Chene\CollectionCheneRecherche;
use \App\Form\Chene\CollectionCheneRechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @Route("/chene/collection")
 */
class CollectionCheneController extends AbstractController {

    /**
     * @var CollectionCheneRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var string
     */
    private $menuCourant = "CollectionChene";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";

    
    public function __construct(CollectionCheneRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="collectionChene.index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete ): Response {
        $collectionChene = $this->repository->findAll();
        
        return $this->render('chene/collectionChene/index.html.twig', [
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant,
                    'collections_chene' => $collectionChene,
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="collectionChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param CollectionChene $je()uEnChene
     * @return Response
     */
    public function show(CollectionChene $collectionChene, string $slug): Response {
        if ($collectionChene->getSlug() !== $slug)
            return $this->redirectToRoute('CollectionChene.show', [
                        'id' => $collectionChene->getId(),
                        'slug' => $collectionChene->getSlug()
                            ], 301);

        return $this->render('chene/collectionChene/show.html.twig', [
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant,
                    'collectionChene' => $collectionChene
        ]);
    }

}
