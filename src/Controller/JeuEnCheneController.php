<?php

namespace App\Controller;

use App\Entity\JeuEnChene;
use App\Repository\JeuEnCheneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;

class JeuEnCheneController extends AbstractController {

    /**
     * @var JeuEnCheneRepository
     */
    private $repository;    
    
     /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(JeuEnCheneRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/jeuEnChene", name="jeuEnChene.index")  
     * @return Response
     */
    public function index(): Response {
        $em = $this->getDoctrine()->getManager();

        $jeuxEnChene = $this->repository->findAllDisponible();

        return $this->render('pages/chene/jeuEnChene/index.html.twig', [
            'menu_courant' => 'Chêne',
            'jeux_en_chene' => $jeuxEnChene        
            ]);
    }

    /**
     * @route("/jeuEnChene/{slug}-{id}", name="jeuEnChene.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $jeuEnChene
     * @return Response
     */
    public function show(JeuEnChene $jeuEnChene, string $slug): Response {
        if ($jeuEnChene->getSlug() !== $slug)
            return $this->redirectToRoute('JeuEnChene.show', [
                'id' => $jeuEnChene->getId(),
                'slug' => $jeuEnChene->getSlug()
                ], 301);

        return $this->render('pages/chene/jeuEnChene/show.html.twig', [
            'jeu_en_chene' => $jeuEnChene,
            'menu_courant' => 'Chêne'
            ]);
    }

}
