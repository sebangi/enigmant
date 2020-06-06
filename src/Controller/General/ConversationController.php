<?php

namespace App\Controller\General;

use App\Entity\General\Conversation;
use App\Entity\General\Message;
use App\Entity\General\User;
use App\Repository\General\ConversationRepository;
use App\Repository\General\MessageRepository;
use App\Repository\General\UserRepository;
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

    protected function getThemeCourant(): string {
        return "General";
    }

    protected function getMenuCourant(): string {
        return "Conversations";
    }

    public function __construct(EntityManagerInterface $em, ConversationRepository $repository) {
        parent::__construct($em);
        $this->repository = $repository;
    }

    public function test_non_appartenance($conversation) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                return ($conversation->getUser()->getId() != $this->getUser()->getId());
            } else
                return true;
        } else
            return false;
    }

    /**
     * @route("/", name="index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete): Response {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            $conversations = $this->repository->getByDate(null);
        else
            $conversations = $this->repository->getByDate($this->getUser()->getId());

        return $this->monRender('/general/conversation/index.html.twig', [
                    'conversations' => $conversations,
        ]);
    }

    public function marquer($messages, $est_admin) {
        foreach ($messages as $message) {
            if ($est_admin) {
                if ($message->getEnCoursLectureGourou()) {
                    $message->setEnCoursLectureGourou(false);
                }
                if (!$message->getVuGourou()) {
                    $message->setEnCoursLectureGourou(true);
                    $message->setVuGourou(true);
                }
            } else {
                if ($message->getEnCoursLecture()) {
                    $message->setEnCoursLecture(false);
                }
                if (!$message->getVu()) {
                    $message->setEnCoursLecture(true);
                    $message->setVu(true);
                }
            }

            $this->em->persist($message);
        }
        $this->em->flush();
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

        if ($this->test_non_appartenance($conversation))
            return $this->redirectToRoute('general.conversation.index');

        $messages = $m_repository->findBy(array("conversation" => $conversation->getId()), array("date" => "ASC"));

        $est_admin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $this->marquer($messages, $est_admin);

        // Formulaire pour un nouveau message
        $message = new Message();
        $message->setConversation($conversation);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $message->setDate(new \DateTime('now'));
            $message->setMessageGourou($est_admin);
            $message->setVu(!$est_admin);
            $message->setVuGourou($est_admin);
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

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $conversation->setCreeParGourou(true);
        } else {
            $conversation->setUser($this->getUser());
        }

        $form = $this->createForm(ConversationType::class, $conversation,
                ['administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
                    'user_id' => $this->getUser()->getId()]);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            if (!$form->get('cancel')->isClicked()) {
                $this->em->persist($conversation);

                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    $this->creerMessageInitial($conversation);

                $this->em->flush();

                return $this->redirectToRoute('general.conversation.show', [
                            'id' => $conversation->getId(),
                            'slug' => $conversation->getSlug()
                ]);
            } else
                return $this->redirectToRoute('general.conversation.index');
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
        if ($this->test_non_appartenance($conversation))
            return $this->redirectToRoute('general.conversation.index');

        $form = $this->createForm(ConversationType::class, $conversation,
                ['administration' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
                    'user_id' => $this->getUser()->getId()]);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            if (!$form->get('cancel')->isClicked()) {
                $this->em->flush();
                $this->addFlash('success', 'Conversation modifiée avec succès.');
            }
            return $this->redirectToRoute('general.conversation.index');
        }

        return $this->monRender('general/conversation/edit.html.twig', [
                    'conversation' => $conversation,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/{id}/editMessage", name="editMessage", methods="GET|POST")  
     * @param Conversation $conversation
     * @param Request $requete
     * @return Response
     */
    public function editMessage(Message $message, Request $requete): Response {
        if ($this->test_non_appartenance($message->getConversation()))
            return $this->redirectToRoute('general.conversation.index');

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($requete);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Message modifié avec succès.');

            return $this->redirectToRoute('general.conversation.show', [
                        'id' => $message->getConversation()->getId(),
                        'slug' => $message->getConversation()->getSlug(),
                        '_fragment' => 'message-' . $message->getId() 
            ]);
        }

        return $this->monRender('general/conversation/messageEdit.html.twig', [
                    'message' => $message,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @route("/{id}/supprimerMessage", name="supprimerMessage", methods="DELETE")  
     * @param Conversation $conversation
     * @param Request $requete
     * @return Response
     */
    public function deleteMessage(Message $message, Request $requete) {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $requete->get('_token'))) {
            $this->em->remove($message);
            $this->em->flush();

            $this->addFlash('success', 'Message supprimé avec succès.');
        }

        return $this->redirectToRoute('general.conversation.show', [
                        'id' => $message->getConversation()->getId(),
                        'slug' => $message->getConversation()->getSlug(),
                        '_fragment' => "nouveau-message"
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
