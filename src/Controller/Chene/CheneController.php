<?php

namespace App\Controller\Chene;

use App\Repository\Chene\JeuEnCheneRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
     * @route("/chene") 
     */
class CheneController extends BaseController
{    
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "EnigmesEnChene";
    }
        
    /**
     * @route("/", name="chene.home")  
     * @return Response
     */
    public function home( JeuEnCheneRepository $repository ) : Response
    {
        $jeuxEnChene = $repository->findDerniersDisponible();
        
        return $this->monRender('chene/home.html.twig', [
            'jeux_en_chene' => $jeuxEnChene
        ]);
    }
    
}


