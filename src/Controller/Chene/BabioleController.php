<?php

namespace App\Controller\Chene;

use App\Entity\Chene\Babiole;
use App\Repository\Chene\BabioleRepository;
use \App\Entity\Chene\BabioleRecherche;
use \App\Form\Chene\BabioleRechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @Route("/chene/babiole")
 */
class BabioleController extends AbstractController {

    /**
     * @var BabioleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var string
     */
    private $menu_courant = "Babiole";
    
    /**
     * @var string
     */
    private $theme_courant = "Chêne";
    

    public function __construct(BabioleRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="babiole.index")  
     * @var PaginatorInterface $paginator
     * @var Request $Request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $Requete, CacheManager $imagineCacheManager ): Response {

        $recherche = new BabioleRecherche();
        $form = $this->createForm(BabioleRechercheType::class, $recherche);
        $form->handleRequest($Requete);

        $babioles = $paginator->paginate(
                $this->repository->findAllQuery($recherche),
                $Requete->query->getInt('page', 1),
                6
        );

        return $this->render('chene/babiole/index.html.twig', [
                    'menu_courant' => 'Babiole',
                    'theme_courant' => 'Chêne',
                    'babioles' => $babioles,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="babiole.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param babiole $je()uEnChene
     * @return Response
     */
    public function show(babiole $babiole, string $slug): Response {
        if ($babiole->getSlug() !== $slug)
            return $this->redirectToRoute('babiole.show', [
                        'id' => $babiole->getId(),
                        'slug' => $babiole->getSlug()
                            ], 301);

        return $this->render('chene/babiole/show.html.twig', [
                    'babiole' => $babiole,
                    'menu_courant' => 'Babiole',
                    'theme_courant' => 'Chêne'
        ]);
    }

}