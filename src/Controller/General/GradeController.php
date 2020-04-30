<?php

namespace App\Controller\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\Niveau;
use App\Repository\General\NiveauRepository;
use App\Entity\General\Theme;
use App\Repository\General\ThemeRepository;
use App\Repository\General\UserRepository;
use App\Repository\General\ObtentionNiveauRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/general/grade")
 */
class GradeController extends BaseController
{    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "Grades";
    }

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    } 
        
    /**
     * @Route("/{themeCourant}", name="grade.index", methods={"GET"})
     * @param NiveauRepository $niveauRepository
     * @param string $themeCourant
     * @return Response
     */
    public function index(
            ObtentionNiveauRepository $optRepository, 
            NiveauRepository $niveauRepository,
            ThemeRepository $themeRepository,
            string $themeCourant): Response
    {       
        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ||
             $this->get('security.authorization_checker')->isGranted('ROLE_USER') )
        {           
            $themes = $themeRepository->findAll();
            
            if( $themeCourant == "General" )
            {
                $grades = [];
                foreach ($themes as $t)
                {
                    $obts = $optRepository->getPlusHautGradesTheme($this->getUser()->getId(), $t->getId());
                    if ( $obts )
                        $grades[] = $obts[0];
                }
                $nouveaux_grades = $optRepository->getNouveauxGrades($this->getUser()->getId());
            }
            else
            {
                $grades = $niveauRepository->getGradesDunTheme($themeCourant);
                $nouveaux_grades = $optRepository->getNouveauxGradesTheme($this->getUser()->getId(), $themeCourant);
            }
            
            
            foreach ($nouveaux_grades as $nouveau_grade) {
                $this->get('session')->getFlashBag()->add('nouveaux_grades', array('type' => 'success',
                        "message" => 'Vous êtes maintenant ' . $nouveau_grade->getNiveau()->getGrade(), 
                        "title" => $nouveau_grade->getNiveau()->getRaison() ) );
                
                $nouveau_grade->setVu(true);
                $this->em->persist( $nouveau_grade );
                $this->em->flush();
            }
            
            return $this->monRender('general/grade/index.html.twig', [
                'themeCourant' => $themeCourant,
                'themes' => $themes,
                'grades' => $grades,
                'user' => $this->getUser(),
            ]);
        }
        else
            $this->redirectToRoute('home');
    }
}
