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
    private $menu_courant = "EnigmesEnChasse";
    
    /**
     * @var string
     */
    private $theme_courant = "Chasse";
        
    /**
     * @route("/", name="chasse.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->render('chasse/home.html.twig', [
            'menu_courant' => $this->menu_courant,
            'theme_courant' => $this->theme_courant
        ]);
    }
    
}


