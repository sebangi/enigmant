<?php

namespace App\Controller;

use App\Repository\JeuEnCheneRepository;
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
    public function index( JeuEnCheneRepository $repository ) : Response
    {
        $jeuxEnChene = $repository->findDerniersDisponible();
        
        return $this->render('pages/home.html.twig', [
            'jeuxEnChene' => $jeuxEnChene
        ]);
    }
    
}


