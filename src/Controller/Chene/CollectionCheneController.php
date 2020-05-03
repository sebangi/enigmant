<?php

namespace App\Controller\Chene;

use App\Controller\BaseController;
use App\Entity\Chene\CollectionChene;
use App\Entity\General\User;
use App\Repository\General\UserRepository;
use App\Repository\Chene\CollectionCheneRepository;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Entity\Chene\CollectionCheneRecherche;
use App\Form\Chene\CollectionCheneRechercheType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/chene/collection")
 */
class CollectionCheneController extends BaseController {

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
    
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "CollectionChene";
    }
    
    /**
     * @route("/", name="collectionChene.index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete ): Response {
        $collectionChene = $this->repository->findAllAvecJeu();
        
        return $this->monRender('chene/collectionChene/index.html.twig', [
                    'collections_chene' => $collectionChene,
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="collectionChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param CollectionChene $je()uEnChene
     * @return Response
     */
    public function show(JeuEnCheneRepository $j_repository, CollectionChene $collectionChene, string $slug): Response {
        if ($collectionChene->getSlug() !== $slug)
            return $this->redirectToRoute('CollectionChene.show', [
                        'id' => $collectionChene->getId(),
                        'slug' => $collectionChene->getSlug()
                            ], 301);

        $jeuxEnChene = $j_repository->findBy(array("collectionChene" => $collectionChene->getId()), array("num" => "ASC"));
        
        
        return $this->monRender('chene/collectionChene/show.html.twig', [
                    'collectionChene' => $collectionChene,
                    'jeux_en_chene' => $jeuxEnChene
        ]);
    }

}
