<?php

namespace App\Controller;

use App\Entity\JeuEnChene;
use App\Repository\JeuEnCheneRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ObjectManager;

use Twig\Environment;

class JeuEnCheneController extends AbstractController
{
    
    /**
     * @var JeuEnCheneRepository
     */
    private $repository;
        
    /**
     * @var ObjectManager
     */
    private $em;
    
    public function __construct(JeuEnCheneRepository $repository)
    {
        $this->repository = $repository;
    }    
    
    /**
     * @route("/JeuEnChene", name="JeuEnChene.index")  
     * @return Response
     */
    public function index() : Response
    {
        $em = $this->getDoctrine()->getManager();
        
        $jeuEnChene = $this->repository->findAllDisponible();
        
        return $this->render('pages/chene/jeuEnChene/index.html.twig',
                [ 'menu_courant' => 'Chêne' ]);
    }
    
    /**
     * @route("/JeuEnChene/{slug}-{id}", name="JeuEnChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $jeuEnChene
     * @return Response
     */
    public function show(JeuEnChene $jeuEnChene, string $slug): Response {
        if ( $jeuEnChene->getSlug() !== $slug ) 
            return $this->redirectToRoute( 'JeuEnChene.show', [
                'id' => $jeuEnChene->getId(),
                'slug' => $jeuEnChene->getSlug()
            ], 301);
        
        return $this->render('pages/chene/jeuEnChene/show.html.twig',
            [   'jeuEnChene' => $jeuEnChene,
                'menu_courant' => 'Chêne' ]);
    }

}