<?php

namespace App\Controller\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\Niveau;
use App\Repository\General\NiveauRepository;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use App\Repository\General\ObtentionNiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grades")
 */
class GradeController extends AbstractController
{    
    /**
     * @var string
     */
    private $menuCourant = "Grades";
        
    /**
     * @Route("/{themeCourant}", name="grade.index", methods={"GET"})
     * @param NiveauRepository $niveauRepository
     * @param string|null $themeCourant
     * @return Response
     */
    public function index(NiveauRepository $niveauRepository, ThemeRepository $themeRepository,
                    ?string $themeCourant): Response
    {       
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ||
             $this->get('security.authorization_checker')->isGranted('ROLE_USER') )
        {           
            if( ! $themeCourant )
                $themeCourant = "Chêne";
            $themes = $themeRepository->findAll();
//            $grades = $niveauRepository->getGrades($this->getUser()->getId(), $themeCourant);  
            $grades = $niveauRepository->getGradesDunTheme($themeCourant);
            
            return $this->render('general/grade/index.html.twig', [
                'menuCourant' => $this->menuCourant,
                'themeCourant' => $themeCourant,
                'themes' => $themes,
                'grades' => $grades,
                'user' => $this->getUser(),
            ]);
        }
        else
            $this->redirectToRoute('home.index');
    }
}
