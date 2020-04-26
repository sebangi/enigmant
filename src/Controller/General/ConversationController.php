<?php

namespace App\Controller\General;

use App\Entity\General\Conversation;
use App\Entity\General\Message;
use App\Repository\General\ConversationRepository;
use App\Repository\General\MessageRepository;
use App\Form\General\ConversationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/general/conversation", name="general.conversation.")
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
    
    /**
     * @var string
     */
    private $themeCourant = "General";
    
    
    public function __construct(ConversationRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete ): Response {
        
        $conversations = $this->repository->getByDate( $this->getUser()->getId() );            
        
        return $this->render('/general/conversation/index.html.twig', [
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant,
                    'conversations' => $conversations,
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param conversation $conversation
     * @return Response
     */
    public function show(MessageRepository $m_repository, conversation $conversation, string $slug): Response {
        if ($conversation->getSlug() !== $slug)
            return $this->redirectToRoute('conversation.show', [
                        'id' => $conversation->getId(),
                        'slug' => $conversation->getSlug()
                            ], 301);
        
        $messages = $m_repository->findBy(array("conversation" => $conversation->getId()), array("date" => "DESC"));

        return $this->render('/general/conversation/show.html.twig', [
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant,
                    'conversation' => $conversation,
                    'messages' => $messages
        ]);
    }

    public function creerMessageInitial(Conversation $conversation) 
    {
        $mess = new Message();
        $mess->setConversation($conversation);
        $mess->getDate( new \DateTime('now') );
        $mess->setMessageGourou(true);
        $mess->setTexte("Je vous écoute...");
        $mess->setVu(true);
        $mess->setVuGourou(false);
        $this->em->persist($mess);
    }
    
    /**
     * @route("/new", name="new")  
     * @param Request $requete
     * @return Response
     */
    public function new(Request $requete) {
        $conversation = new Conversation();
        
        if ( ! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
            $conversation->setUser($this->getUser());
        
        $form = $this->createForm(ConversationType::class, $conversation, 
            [   'administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'), 
                'user_id' => $this->getUser()->getId() ] );
        

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($conversation); 
            
            $this->creerMessageInitial($conversation);            
            
            $this->em->flush();
            return $this->redirectToRoute('general.conversation.show', [
                    'id' => $conversation->getId(),
                    'slug' => $conversation->getSlug()
                ]);
        }

        return $this->render('general/conversation/new.html.twig', [
                    'conversation' => $conversation,
                    'form' => $form->createView(),
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
        ]);
    }
        
    /**
     * @route("/{id}/edit", name="edit", methods="GET|POST")  
     * @param Conversation $conversation
     * @param Request $requete
     * @return Response
     */
    public function edit(Conversation $conversation, Request $requete): Response {
        
        $form = $this->createForm(ConversationType::class, $conversation, 
            [   'administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'), 
                'user_id' => $this->getUser()->getId() ]);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Conversation modifiée avec succès.');
            return $this->redirectToRoute('general.conversation.index');
        }

        return $this->render('general/conversation/edit.html.twig', [
                    'conversation' => $conversation,
                    'form' => $form->createView(),
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
        ]);
    }

    /**
     * @route("/{id}", name="delete", methods="DELETE")  
     * @param Conversation $conversation
     * @param Request $requete
     * @return Response
     */
    public function delete(Conversation $conversation, Request $requete) {
        if ($this->isCsrfTokenValid('delete' . $conversation->getId(), $requete->get('_token'))) {
            $this->em->remove($conversation);
            $this->em->flush();

            $this->addFlash('success', 'Conversation supprimée avec succès.');
        }

        return $this->redirectToRoute('general.conversation.index');
    }
}
