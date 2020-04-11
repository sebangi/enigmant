<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Twig\Environment;

class JeuEnChaineController extends AbstractController
{
    
    /**
     * @route("/JeuEnChenes", name="JeuEnChene.index")  
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('pages/chene/jeuEnChene/index.html.twig',
                [ 'menu_courant' => 'Chêne' ]);
    }
    
}