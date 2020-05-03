<?php

namespace App\Controller\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use \App\Entity\Chene\JeuEnCheneRecherche;
use \App\Form\Chene\JeuEnCheneRechercheType;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @Route("/chene/jeu-en-chene")
 */
class JeuEnCheneController extends BaseController {

    /**
     * @var JeuEnCheneRepository
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
        return "JeuEnChene";
    }

    
    public function __construct(JeuEnCheneRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="jeuEnChene.index")  
     * @var PaginatorInterface $paginator
     * @var Request $Request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $Requete, CacheManager $imagineCacheManager ): Response {
        $recherche = new JeuEnCheneRecherche();
        $form = $this->createForm(JeuEnCheneRechercheType::class, $recherche);
        $form->handleRequest($Requete);

        $nbJeuxEnChene = $this->repository->count(['construit' => 'true']);
        
        $jeuxEnChene = $paginator->paginate(
                $this->repository->findAllConstruitQuery($recherche),
                $Requete->query->getInt('page', 1),
                6
        );

        return $this->monRender('chene/jeuEnChene/index.html.twig', [
                    'nb_jeux_en_chene' => $nbJeuxEnChene,
                    'jeux_en_chene' => $jeuxEnChene,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="jeuEnChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $je()uEnChene
     * @return Response
     */
    public function show(JeuEnChene $jeuEnChene, string $slug): Response {
        if ($jeuEnChene->getSlug() !== $slug)
            return $this->redirectToRoute('JeuEnChene.show', [
                        'id' => $jeuEnChene->getId(),
                        'slug' => $jeuEnChene->getSlug()
                            ], 301);

        return $this->monRender('chene/jeuEnChene/show.html.twig', [
                    'jeuEnChene' => $jeuEnChene
        ]);
    }

}
