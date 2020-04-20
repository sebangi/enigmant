<?php

namespace App\Controller\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\Niveau;
use App\Repository\General\NiveauRepository;
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
     * @Route("/", name="grade.index", methods={"GET"})
     * @param NiveauRepository $niveauRepository
     * @param string|null $themeCourant
     * @return Response
     */
    public function index(NiveauRepository $niveauRepository, ?string $themeCourant): Response
    {       
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//        if ( $this->get('security.context')->isGranted('ROLE_ADMIN') ||
//             $this->get('security.context')->isGranted('ROLE_USER') )
//        {           
            $grades =  $niveauRepository->getGrades($this->getUser()->getId(), $themeCourant);
            dump($grades);     
            
            return $this->render('general/grade/index.html.twig', [
                'menuCourant' => $this->menuCourant,
                'themeCourant' => $themeCourant,
                'grades' => $grades,
            ]);
//        }
//        else
//            $this->redirectToRoute('home.index');
    }
}
