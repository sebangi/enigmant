<?php

namespace App\Controller\Chene;

use App\Repository\General\ActualiteRepository;
use App\Controller\BaseController;
use App\Repository\General\NiveauRepository;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Repository\Chene\CollectionCheneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @route("/chene") 
 */
class CheneController extends BaseController {

    protected function getThemeCourant(): string {
        return "Chêne";
    }

    protected function getMenuCourant(): string {
        return "EnigmesEnChene";
    }

    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * 
     * @route("/", name="chene.home")  
     * @param PaginatorInterface $paginator
     * @param Request $Requete
     * @param JeuEnCheneRepository $repository
     * @return Response
     */
    public function home(PaginatorInterface $paginator, Request $Requete, ActualiteRepository $repository): Response {
        $actualites = $paginator->paginate(
                $repository->findDernieresActialites($this->getThemeCourant()),
                $Requete->query->getInt('page', 1),
                4
        );

        return $this->monRender('chene/home.html.twig', [
                    'pagination' => $paginator,
                    'actualites' => $actualites
        ]);
    }

    /**
     * @Route("/grade", name="gradeChene")
     * @param NiveauRepository $niveauRepository
     * @return Response
     */
    public function grade(NiveauRepository $niveauRepository): Response {
        $grades = $niveauRepository->getGradesDunTheme($this->getThemeCourant());

        return $this->monRender('chene/grade.html.twig', [
                    'menuCourant' => 'Grade',
                    'grades' => $grades
        ]);
    }

    /**
     * @Route("/progression", name="progressionChene")
     * @param NiveauRepository $niveauRepository
     * @return Response
     */
    public function progression(
            CollectionCheneRepository $collectionCheneRepository,
            JeuEnCheneRepository $jeuEnCheneRepository): Response {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ||
                $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $collections = $collectionCheneRepository->findBy(array(), array('num'=>'asc'));
            $jeuxEnChene = $jeuEnCheneRepository->findAllByCollection();
            
            return $this->monRender('chene/progression.html.twig', [
                        'menuCourant' => 'Progression',
                        'collections' => $collections,
                        'jeuxEnChene' => $jeuxEnChene,
                        'user' => $this->getUser()
            ]);
        } else
            $this->redirectToRoute('home');
    }

}
