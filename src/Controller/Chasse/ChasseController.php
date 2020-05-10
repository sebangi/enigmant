<?php

namespace App\Controller\Chasse;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\General\ActualiteRepository;

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
    
}


