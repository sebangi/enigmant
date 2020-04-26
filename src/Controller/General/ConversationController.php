<?php

namespace App\Controller\General;

use App\Entity\General\Conversation;
use App\Repository\General\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/general/conversation")
 */
class ConversationController extends AbstractController {

    /**
     * @var ConversationRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var string
     */
    private $menuCourant = "Conversation";
    
    
    public function __construct(ConversationRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="conversation.index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete ): Response {
        
        $conversations = $this->repository->getByDate( $this->getUser()->getId() );            
        
        return $this->render('/general/conversation/index.html.twig', [
                    'menuCourant' => 'Conversation',
                    'themeCourant' => 'General',
                    'conversations' => $conversations,
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="conversation.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param conversation $conversation
     * @return Response
     */
    public function show(conversation $conversation, string $slug): Response {
        if ($conversation->getSlug() !== $slug)
            return $this->redirectToRoute('conversation.show', [
                        'id' => $conversation->getId(),
                        'slug' => $conversation->getSlug()
                            ], 301);

        return $this->render('/general/conversation/show.html.twig', [
                    'conversation' => $conversation,
                    'menuCourant' => 'Conversation',
                    'themeCourant' => 'General'
        ]);
    }

}
