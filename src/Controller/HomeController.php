<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\General\ThemeRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends BaseController
{    
    
    protected function getThemeCourant() : ?string
    {
        return "General";
    }
    
    protected function getMenuCourant() : ?string
    {
        return null;
    }
    
    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * @route("/", name="home")  
     * @return Response
     */
    public function index(ThemeRepository $t_repository, UserPasswordEncoderInterface $passwordEncoder ) : Response
    {
          $themes = $t_repository->findAll();
        
//        
//        $user = $urep->findOneBy(['username' => 'demo']);   
//        $user->setPassword( $passwordEncoder->encodePassword($user, "demo")  );
//        $em->persist($user);
//        $em->flush();
//                
        return $this->monRender('home.html.twig', [
            'themes' => $themes
        ]);
    }
    
    /**
     * @route("/ligne", name="ligne.home")  
     * @return Response
     */
    public function ligneHome( ) : Response
    {
        return $this->monRender('nonDisponible.html.twig', [
            "theme" => "Ligne"
        ]);
    }
        
    /**
     * @route("/rallye", name="rallye.home")  
     * @return Response
     */
    public function rallyeHome( ) : Response
    {
        return $this->monRender('nonDisponible.html.twig', [
            "theme" => "Rallye"
        ]);
    }
    
    /**
     * @route("/fanteasy", name="fanteasy.home")  
     * @return Response
     */
    public function fanteasyHome( ) : Response
    {
        return $this->monRender('nonDisponible.html.twig', [
            "theme" => "Fanteasy"
        ]);
    }    
}


