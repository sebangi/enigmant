<?php

namespace App\Controller;

use App\Repository\Chene\JeuEnCheneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
   /*
     * @route("/login", name="login")
     * @return Response
     */
    public function login() : Response {
        return $this->render( 'security/login.html.twig');
    }
    
    /**
     * @route("/", name="home.index")  
     * @return Response
     */
    public function index( JeuEnCheneRepository $repository ) : Response
    {
        $jeuxEnChene = $repository->findDerniersDisponible();
        
        return $this->render('home.html.twig', [
            'jeux_en_chene' => $jeuxEnChene
        ]);
    }
    
}


