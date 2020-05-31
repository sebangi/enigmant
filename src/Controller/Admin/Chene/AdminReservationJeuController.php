<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\ReservationJeu;
use App\Form\Chene\ReservationJeuType;
use App\Repository\Chene\ReservationJeuRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\General\Message;
use App\Entity\General\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/chene/reservation")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminReservationJeuController extends BaseController {

    protected function getThemeCourant(): string {
        return "Chêne";
    }

    protected function getMenuCourant(): string {
        return "AdminReservation";
    }

    
    /**
     * @var ReservationJeuRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ReservationJeuRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    
    /**
     * @Route("/", name="admin.chene.reservation.index", methods={"GET"})
     */
    public function index(ReservationJeuRepository $reservationJeuRepository): Response {
        return $this->monRender('admin/chene/reservation/index.html.twig', [
                    'reservations' => $reservationJeuRepository->findBy([], ['dateDemande' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="admin.chene.reservation.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $reservation = new ReservationJeu();
        $reservation->setDateDemande(new \DateTime('now'));
        $form = $this->createForm(ReservationJeuType::class, $reservation,
                ['administration' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation ajoutée avec succès.');

            return $this->redirectToRoute('admin.chene.reservation.index');
        }

        return $this->monRender('admin/chene/reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.chene.reservation.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReservationJeu $reservation): Response {
        $form = $this->createForm(ReservationJeuType::class, $reservation,
                ['administration' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Réservation modifiée avec succès.');

            return $this->redirectToRoute('admin.chene.reservation.index');
        }

        return $this->monRender('admin/chene/reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @param \App\Controller\Admin\Chene\Conversation $conversation
     * @return Message
     */
    private function creerMessage(Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(true);
        $message->setVu(false);
        $message->setVuGourou(true);

        return $message;
    }

    /**
     * 
     * @param ReservationJeu $reservation
     */
    private function creerMessageRetraitPret(ReservationJeu $reservation) {

        $message = $this->creerMessage($reservation->getConversation());

        if ( $reservation->getRetraitDomicile() )
        {
            $message->setTexte("Le Jeu en Chêne est prêt. Vous pouvez venir le retirer à la date prévue.");
        }
        else {
            $message->setTexte("Le rendez-vous convenu est confirmé.");
        }
        
        $this->em->persist($message);
    }
    
    
    /**
     * 
     * @param ReservationJeu $reservation
     */
    private function creerMessageJouer(ReservationJeu $reservation) {

        $message = $this->creerMessage($reservation->getConversation());
        $message->setTexte("Amusez-vous bien !");
        
        $this->em->persist($message);
    }

    /**
     * @Route("/{id}/editEtat", name="admin.chene.reservation.edit.etat", methods={"GET","POST"})
     */
    public function editEtat(Request $request, ReservationJeu $reservation): Response {
        $form = $this->createForm(ReservationJeuType::class, $reservation,
                ['champ' => "etat"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('cancel')->isClicked()) {
                if ($reservation->getEtat() === 1)
                    $this->creerMessageRetraitPret($reservation);
                else if ($reservation->getEtat() === 2)
                    $this->creerMessageJouer($reservation);
                    

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Réservation modifiée avec succès.');
            }

            return $this->redirectToRoute('chene.location.show', [
                        'id' => $reservation->getId(),
                        'slug' => $reservation->getSlug()
            ]);
        }

        return $this->monRender('admin/chene/reservation/edit_etat.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.chene.reservation.delete", methods={"DELETE"})
     */
    public function delete(Request $request, ReservationJeu $reservation): Response {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.chene.reservation.index');
    }

}
