<?php

namespace App\Controller\Admin\Chene;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
     * @route("/admin/chene") 
     */
class AdminCheneController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menu_courant = "Chêne";
    
    /**
     * @var string
     */
    private $theme_courant = "Chêne";
        
    /**
     * @route("/", name="admin.chene.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->render('admin/chene/home.html.twig', [
            'menu_courant' => $this->menu_courant,
            'theme_courant' => $this->theme_courant
        ]);
    }
    
}


