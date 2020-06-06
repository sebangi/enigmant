<?php

namespace App\Controller\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Repository\Chene\ReservationJeuRepository;
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


    protected function getThemeCourant(): string {
        return "Chêne";
    }

    protected function getMenuCourant(): string {
        return "JeuEnChene";
    }

    public function __construct(JeuEnCheneRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @route("/", name="jeuEnChene.index")  
     * @var PaginatorInterface $paginator
     * @var Request $Request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $Requete, CacheManager $imagineCacheManager): Response {
        $recherche = new JeuEnCheneRecherche();
        $form = $this->createForm(JeuEnCheneRechercheType::class, $recherche);
        $form->handleRequest($Requete);

        $filtre = false;
        if ($form->get('recherche')->isClicked())
            $filtre = true;

        $nbJeuxEnChene = $this->repository->count(['construit' => 'true']);

        $tous = $this->repository->findAllConstruit($recherche);

        $jeuxEnChene = $paginator->paginate(
                $tous,
                $Requete->query->getInt('page', 1),
                6
        );

        return $this->monRender('chene/jeuEnChene/index.html.twig', [
                    'option_recherche' => true,
                    'pagination' => $paginator,
                    'filtre' => $filtre,
                    'nb_jeux_en_chene' => count($tous),
                    'jeux_en_chene' => $jeuxEnChene,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="jeuEnChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $jeuEnChene
     * @param ReservationJeuRepository $resRep
     * @return Response
     */
    public function show(ReservationJeuRepository $resRep, JeuEnChene $jeuEnChene, string $slug): Response {
        if ($jeuEnChene->getSlug() !== $slug)
            return $this->redirectToRoute('JeuEnChene.show', [
                        'id' => $jeuEnChene->getId(),
                        'slug' => $jeuEnChene->getSlug()
                            ], 301);

        $jeuPrecedent = $this->repository->findOneBy(
                ["num" => $jeuEnChene->getNum() - 1,
                    "collectionChene" => $jeuEnChene->getCollectionChene()]);
        $jeuSuivant = $this->repository->findOneBy(
                ["num" => $jeuEnChene->getNum() + 1,
                    "collectionChene" => $jeuEnChene->getCollectionChene()]);

        $reservations = $resRep->findAllReservationsAvecAvis($jeuEnChene->getId());
        $moyenne = $resRep->findNoteMoyenne($jeuEnChene->getId());
        $cardNotes = 0;
        for ($i = 0; $i <= 5; $i++) {
            $rep = $resRep->findNbNotes($jeuEnChene->getId(), $i);
            if (count($rep) != 0) {
                $nbNotes[$i] = intval( $resRep->findNbNotes($jeuEnChene->getId(), $i)[0][1] );
                $cardNotes = $cardNotes + $nbNotes[$i];
            } else {
                $nbNotes[$i] = 0;
            }
        }

        return $this->monRender('chene/jeuEnChene/show.html.twig', [
                    'jeuEnChene' => $jeuEnChene,
                    'jeuPrecedent' => $jeuPrecedent,
                    'jeuSuivant' => $jeuSuivant,
                    'reservations' => $reservations,
                    'moyenne' => $moyenne,
                    'nbNotes' => $nbNotes,
                    'cardNotes' => $cardNotes,
        ]);
    }

}
