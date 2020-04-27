<?php

namespace App\Controller\General;

use App\Entity\General\Conversation;
use App\Entity\General\Message;
use App\Repository\General\ConversationRepository;
use App\Repository\General\MessageRepository;
use App\Form\General\ConversationType;
use App\Form\General\MessageType;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/general/conversation", name="general.conversation.")
 */
class ConversationController extends BaseController {

    /**
     * @var ConversationRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected function getThemeCourant(): string {
        return "General";
    }

    protected function getMenuCourant(): string {
        return "Conversations";
    }

    public function __construct(ConversationRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/", name="index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete): Response {

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            $conversations = $this->repository->getByDate(null);
        else
            $conversations = $this->repository->getByDate($this->getUser()->getId());

        return $this->monRender('/general/conversation/index.html.twig', [
                    'conversations' => $conversations,
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param conversation $conversation
     * @return Response
     */
    public function show(Request $requete, MessageRepository $m_repository, conversation $conversation, string $slug, string $anchor = ""): Response {
        if ($conversation->getSlug() !== $slug)
            return $this->redirectToRoute('conversation.show', [
                        'id' => $conversation->getId(),
                        'slug' => $conversation->getSlug()], 301);

        $messages = $m_repository->findBy(array("conversation" => $conversation->getId()), array("date" => "ASC"));
                    
        // Formulaire pour un nouveau message
        $message = new Message();
        $message->setConversation($conversation);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $message->setDate(new \DateTime('now'));
            $admin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
            $message->setMessageGourou($admin);
            $message->setVu(!$admin);
            $message->setVuGourou($admin);
            dump($message);
            $this->em->persist($message);
            $this->em->flush();
            
            
            return $this->redirectToRoute('general.conversation.show', [
                        'id' => $conversation->getId(),
                        'slug' => $conversation->getSlug()
            ]);
        }

        return $this->monRender('/general/conversation/show.html.twig', [
                    'form' => $form->createView(),
                    'conversation' => $conversation,
                    'messages' => $messages
        ]);
    }

    public function creerMessageInitial(Conversation $conversation) {
        $mess = new Message();
        $mess->setConversation($conversation);
        $mess->setDate(new \DateTime('now'));
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

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $conversation->setCreeParGourou(true);
        }
        else
        {
            $conversation->setUser($this->getUser());
        }

        $form = $this->createForm(ConversationType::class, $conversation,
                ['administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
                    'user_id' => $this->getUser()->getId()]);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($conversation);

            if ( ! $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                $this->creerMessageInitial($conversation);

            $this->em->flush();
            return $this->redirectToRoute('general.conversation.show', [
                        'id' => $conversation->getId(),
                        'slug' => $conversation->getSlug()
            ]);
        }

        return $this->monRender('general/conversation/new.html.twig', [
                    'conversation' => $conversation,
                    'form' => $form->createView(),
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
                ['administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
                    'user_id' => $this->getUser()->getId()]);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Conversation modifiée avec succès.');
            return $this->redirectToRoute('general.conversation.index');
        }

        return $this->monRender('general/conversation/edit.html.twig', [
                    'conversation' => $conversation,
                    'form' => $form->createView(),
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
