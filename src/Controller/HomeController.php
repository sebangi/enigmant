<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\Chene\JeuEnCheneRepository;

class HomeController extends AbstractController
{    
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


