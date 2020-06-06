<?php

namespace App\Controller\Chasse;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\General\ActualiteRepository;
use App\Repository\General\NiveauRepository;

/**
     * @route("/chasse") 
     */
class ChasseController extends BaseController
{
    protected function getThemeCourant() : string
    {
        return "Chasse";
    }
    
    protected function getMenuCourant() : string
    {
        return "EnigmesEnChasse";
    }
        
    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * @route("/", name="chasse.home")  
     * @param PaginatorInterface $paginator
     * @param Request $Requete
     * @param JeuEnCheneRepository $repository
     * @return Response
     */
    public function home( PaginatorInterface $paginator, Request $Requete, ActualiteRepository $repository ) : Response
    {
        $actualites = $paginator->paginate(
                $repository->findDernieresActialites( $this->getThemeCourant() ),
                $Requete->query->getInt('page', 1),
                6
        );        
        
        return $this->monRender('chasse/home.html.twig', [
            'actualites' => $actualites
        ]);
    }
    
    
    /**
     * @Route("/grade", name="gradeChasse")
     * @param NiveauRepository $niveauRepository
     * @return Response
     */
    public function index(NiveauRepository $niveauRepository): Response {
        $grades = $niveauRepository->getGradesDunTheme($this->getThemeCourant());
        
        return $this->monRender('chasse/grade.html.twig', [
                'menuCourant' => 'Grade',
                'grades' => $grades
        ]);
    }
    
}


