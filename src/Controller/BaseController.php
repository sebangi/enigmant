<?php

namespace App\Controller;

use App\Repository\Chene\CollectionCheneRepository;
use App\Repository\General\UserRepository;
use App\Entity\General\User;
use App\Entity\General\Message;
use App\Repository\General\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController 
{
    abstract protected function getThemeCourant() : ?string;
    abstract protected function getMenuCourant() : ?string;
    
    protected function loadUser() 
    {
        if ( $this->getUser() )
            $this->getDoctrine()->getRepository(User::class)->recupererTout( $this->getUser()->getId() );
    }
    
    protected function monRender( string $view, array $parameters = [], Response $response = null) : Response
    {
        $this->loadUser();
        
        if ( $this->getUser() )
            $nbMessagesNonVus = $this->getDoctrine()->getRepository(Message::class)->getNbMessagesNonLus
                    ($this->getUser()->getId(), $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'));
        else
            $nbMessagesNonVus = 0;
        $parameters['nbMessageNonLus'] = $nbMessagesNonVus;
        
        $parameters['menuCourant'] = $this->getMenuCourant();
        
        if ( ! array_key_exists('themeCourant', $parameters) )
            if( $this->getThemeCourant() ) 
                $parameters['themeCourant'] = $this->getThemeCourant();
        
        return $this->render($view, $parameters, $response);
    }
}
