<?php

namespace App\Controller\Chasse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
     * @route("/chasse") 
     */
class ChasseController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menuCourant = "EnigmesEnChasse";
    
    /**
     * @var string
     */
    private $themeCourant = "Chasse";
        
    /**
     * @route("/", name="chasse.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->render('chasse/home.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant
        ]);
    }
    
}


