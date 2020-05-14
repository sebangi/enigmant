<?php

namespace App\Controller;

use App\Repository\Chene\CollectionCheneRepository;
use App\Repository\General\UserRepository;
use App\Entity\General\User;
use App\Entity\General\Message;
use App\Repository\General\MessageRepository;
use App\Entity\General\Conversation;
use App\Repository\General\ConversationRepository;
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
        // On prend tout du user
        $this->loadUser();
        
        $est_admin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        
        // On compte le nombre de messages non vus
        if ( $this->getUser() )
            $nbMessagesNonVus = $this->getDoctrine()->getRepository(Message::class)->getNbMessagesNonLus
                    ($this->getUser()->getId(), $est_admin);
        else
            $nbMessagesNonVus = 0;
        $parameters['nbMessageNonLus'] = $nbMessagesNonVus;
        
        // On met à jour la conversation non lue
        if ( $nbMessagesNonVus == "1" )
        {
            $parameters['messageNonLu'] =             
                $nbMessagesNonVus = $this->getDoctrine()->getRepository(Message::class)->getFirstConversationNonLus
                    ($this->getUser()->getId(), $est_admin)[0];
        }
        else
        {
            $parameters['messageNonLu'] = null;
        }
        
        // Menu courant
        if ( ! array_key_exists('menuCourant', $parameters) )
            if( $this->getMenuCourant() ) 
                $parameters['menuCourant'] = $this->getMenuCourant();
                
        // Theme courant
        if ( ! array_key_exists('themeCourant', $parameters) )
            if( $this->getThemeCourant() ) 
                $parameters['themeCourant'] = $this->getThemeCourant();
        
        return $this->render($view, $parameters, $response);
    }
}
