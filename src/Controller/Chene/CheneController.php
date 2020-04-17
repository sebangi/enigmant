<?php

namespace App\Controller\Chene;

use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
     * @route("/chene") 
     */
class CheneController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menuCourant = "EnigmesEnChene";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
        
    /**
     * @route("/", name="chene.home")  
     * @return Response
     */
    public function home( JeuEnCheneRepository $repository ) : Response
    {
        $jeuxEnChene = $repository->findDerniersDisponible();
        
        return $this->render('chene/home.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant,
            'jeux_en_chene' => $jeuxEnChene
        ]);
    }
    
}


