<?php

namespace App\Controller;

use App\Repository\Chene\CollectionCheneRepository;
use App\Repository\General\UserRepository;
use App\Entity\General\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController 
{
    abstract protected function getThemeCourant() : ?string;
    abstract protected function getMenuCourant() : ?string;
    
    protected function loadUser() 
    {
        if ( $this->getUser() )
        {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $repository->recupererTout( $this->getUser()->getId() );
        }
    }
    
    protected function monRender( string $view, array $parameters = [], Response $response = null) : Response
    {
        $this->loadUser();
        
        $parameters['menuCourant'] = $this->getMenuCourant();
        
        if ( ! array_key_exists('themeCourant', $parameters) )
            if( $this->getThemeCourant() ) 
                $parameters['themeCourant'] = $this->getThemeCourant();
        
        return $this->render($view, $parameters, $response);
    }
}
