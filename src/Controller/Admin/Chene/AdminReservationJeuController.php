<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\ReservationJeu;
use App\Form\Chene\ReservationJeuType;
use App\Entity\General\Grade;
use App\Repository\Chene\ReservationJeuRepository;
use App\Repository\General\GradeRepository;
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

    public function __construct(EntityManagerInterface $em, ReservationJeuRepository $repository) {
        parent::__construct($em);
        $this->repository = $repository;
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
            $this->em->persist($reservation);
            $this->em->flush();
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
            foreach ($reservation->getBabioles() as $babiole) {
                $babiole->setReservationJeu($reservation);
            }

            $this->em->flush();
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

        if ($reservation->getRetraitDomicile()) {
            $message = $this->creerMessage($reservation->getConversation());
            $message->setTexte("RETRAIT DU JEU EN CHÊNE \n\n"
                    . "Le Jeu en Chêne est prêt.\n\n"
                    . "Vous avez choisi le retrait à Saint Philbert de Grand Lieu.\n"
                    . "Voici l'adresse :\n"
                    . "24 Ter rue des Guittières \n"
                    . "44 310 SAINT PHILBERT DE GRAND LIEU \n\n"
                    . "En cas d'absence de notre part, le Jeu en Chêne avec les instructions sont à retirer dans la boîte en chêne située près du tas de bois. C'est immanquable ! \n\n"
                    . "Le code du cadenas est 8181.\n"
                    . "Déposez à la place vos babioles si vous en avez...");

            $this->em->persist($message);
        } else {
            $message = $this->creerMessage($reservation->getConversation());
            $message->setTexte("Le rendez-vous convenu est confirmé.");
            $this->em->persist($message);
        }
    }

    /**
     * 
     * @param ReservationJeu $reservation
     */
    private function creerMessageRetourOk(ReservationJeu $reservation) {

        $message = $this->creerMessage($reservation->getConversation());

        if ($reservation->getRetourDomicile()) {
            $message->setTexte("Vous pouvez venir retourner le Jeu en Chêne à la date prévue.");
        } else {
            $message->setTexte("Le rendez-vous convenu pour le retour est confirmé.");
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
     * 
     * @param ReservationJeu $reservation
     */
    private function creerMessageRetourEffectue(ReservationJeu $reservation) {

        $message = $this->creerMessage($reservation->getConversation());

        if ($reservation->getReussi()) {
            $message->setTexte(
                    "Bravo ! Vous avez réussi le Jeu en Chêne " . $reservation->getJeu()->getNom() . ".\n"
                    . "Votre progression a été mise à jour !\n"
                    . "Pour terminer, vous pouvez donner votre avis !\n"
                    . "À très bientôt !");
        } else {
            $message->setTexte(
                    "Vous n'avez pas trouvé le médaillon.\n "
                    . "Nous espérons que le Jeu en Chêne vous a tout de même plu ! \n"
                    . "Pour terminer, vous pouvez donner votre avis !\n"
                    . "À très bientôt !");
        }

        $this->em->persist($message);
    }
    
    /**
     * 
     * @param ReservationJeu $reservation
     */
    private function testerPremiereReservation(ReservationJeu $reservation) {
        $grade = $this->getDoctrine()->getRepository(Grade::class)->getGrades($reservation->getUser()->getId(),"Chêne");
        dump($grade);
    
        $this->addFlash('success', 'testerPremiereReservation efectue.');
    }
    
    /**
     * @Route("/{id}/validerRetrait", name="admin.chene.reservation.validerRetrait")
     */
    public function validerRetrait(ReservationJeu $reservation): Response {
        $reservation->setEtat(1);
        $this->creerMessageRetraitPret($reservation);

        $this->em->flush();
        $this->addFlash('success', 'Réservation modifiée avec succès.');


        return $this->redirectToRoute('chene.location.show', [
                    'id' => $reservation->getId(),
                    'slug' => $reservation->getSlug()
        ]);
    }

    /**
     * @Route("/{id}/retraitEffectue", name="admin.chene.reservation.retraitEffectue")
     */
    public function retraitEffectue(ReservationJeu $reservation): Response {
        $reservation->setEtat(2);
        $this->creerMessageJouer($reservation);
        $this->testerPremiereReservation($reservation);

        $this->em->flush();
        $this->addFlash('success', 'Réservation modifiée avec succès.');

        return $this->redirectToRoute('chene.location.show', [
                    'id' => $reservation->getId(),
                    'slug' => $reservation->getSlug()
        ]);
    }

    /**
     * @Route("/{id}/validerRetour", name="admin.chene.reservation.validerRetour")
     */
    public function validerRetour(ReservationJeu $reservation): Response {
        $reservation->setEtat(4);
        $this->creerMessageRetourOk($reservation);

        $this->em->flush();
        $this->addFlash('success', 'Réservation modifiée avec succès.');

        return $this->redirectToRoute('chene.location.show', [
                    'id' => $reservation->getId(),
                    'slug' => $reservation->getSlug()
        ]);
    }

    /**
     * @Route("/{id}-{reussi}/retourEffectue", name="admin.chene.reservation.retourEffectue")
     */
    public function retourEffectue(ReservationJeu $reservation, $reussi): Response {
        $reservation->setEtat(6);
        $reservation->setReussi($reussi == "true");
        $this->creerMessageretourEffectue($reservation);
        $reservation->getJeu()->setDisponible(true);

        $this->em->flush();
        $this->addFlash('success', 'Réservation modifiée avec succès.');
        $this->addFlash('error', 'Le jeu est à nouveau disponible.');

        return $this->redirectToRoute('chene.location.show', [
                    'id' => $reservation->getId(),
                    'slug' => $reservation->getSlug()
        ]);
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
                {
                    $this->creerMessageJouer($reservation);
                    $this->testerPremiereReservation($reservation);
                }
                else if ($reservation->getEtat() === 4)
                    $this->creerMessageRetourOk($reservation);
                else if ($reservation->getEtat() === 5)
                    $this->creerMessageRetourEffectue($reservation);

                $this->em->flush();
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
            $this->em->remove($reservation);
            $this->em->flush();
            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.chene.reservation.index');
    }

}
