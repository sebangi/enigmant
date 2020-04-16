<?php

namespace App\Controller\Admin\Chasse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
     * @route("/admin/chasse") 
     */
class AdminChasseController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menu_courant = "Chasse";
    
    /**
     * @var string
     */
    private $theme_courant = "Chasse";
        
    /**
     * @route("/", name="admin.chasse.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->render('admin/chasse/home.html.twig', [
            'menu_courant' => $this->menu_courant,
            'theme_courant' => $this->theme_courant
        ]);
    }
    
}


