<?php

namespace App\Controller\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use \App\Entity\Chene\JeuEnCheneRecherche;
use \App\Form\Chene\JeuEnCheneRechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

class JeuEnCheneController extends AbstractController {

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
     * @route("/chene/jeu-en-chene", name="jeuEnChene.index")  
     * @var PaginatorInterface $paginator
     * @var Request $Request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $Requete, CacheManager $imagineCacheManager ): Response {
        $recherche = new JeuEnCheneRecherche();
        $form = $this->createForm(JeuEnCheneRechercheType::class, $recherche);
        $form->handleRequest($Requete);

        $jeuxEnChene = $paginator->paginate(
                $this->repository->findAllDisponibleQuery($recherche),
                $Requete->query->getInt('page', 1),
                6
        );

        return $this->render('chene/jeuEnChene/index.html.twig', [
                    'menu_courant' => 'JeuEnChene',
                    'theme_courant' => 'Chêne',
                    'jeux_en_chene' => $jeuxEnChene,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/chene/jeu-en-chene/{slug}-{id}", name="jeuEnChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $je()uEnChene
     * @return Response
     */
    public function show(JeuEnChene $jeuEnChene, string $slug): Response {
        if ($jeuEnChene->getSlug() !== $slug)
            return $this->redirectToRoute('JeuEnChene.show', [
                        'id' => $jeuEnChene->getId(),
                        'slug' => $jeuEnChene->getSlug()
                            ], 301);

        return $this->render('chene/jeuEnChene/show.html.twig', [
                    'menu_courant' => 'JeuEnChene',
                    'jeuEnChene' => $jeuEnChene,
                    'theme_courant' => 'Chêne'
        ]);
    }

}
