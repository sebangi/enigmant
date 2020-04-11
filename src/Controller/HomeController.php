<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    
    /**
     * @route("/", name="Home.index")  
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('pages/home.html.twig');
    }
    
}


