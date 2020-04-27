<?php

namespace App\Controller\Chasse;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->monRender('chasse/home.html.twig');
    }
    
}


