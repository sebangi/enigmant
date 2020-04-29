<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Repository\General\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

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
    
    /**
     * @route("/", name="home.index")  
     * @return Response
     */
    public function index(KernelInterface $kernel, EntityManagerInterface $em, UserRepository $urep, JeuEnCheneRepository $repository, UserPasswordEncoderInterface $passwordEncoder ) : Response
    {
       dump( $kernel->getProjectDir() );
//        
//        $user = $urep->findOneBy(['username' => 'demo']);   
//        $user->setPassword( $passwordEncoder->encodePassword($user, "demo")  );
//        $em->persist($user);
//        $em->flush();
//                
        return $this->monRender('home.html.twig');
    }
    
}


