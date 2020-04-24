<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\Chene\JeuEnCheneRepository;
use App\Repository\General\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{    
    /**
     * @route("/", name="home.index")  
     * @return Response
     */
    public function index(EntityManagerInterface $em, UserRepository $urep, JeuEnCheneRepository $repository, UserPasswordEncoderInterface $passwordEncoder ) : Response
    {
        $jeuxEnChene = $repository->findDerniersDisponible();
        
//        
//        $user = $urep->findOneBy(['username' => 'demo']);   
//        $user->setPassword( $passwordEncoder->encodePassword($user, "demo")  );
//        $em->persist($user);
//        $em->flush();
//                
        return $this->render('home.html.twig');
    }
    
}


