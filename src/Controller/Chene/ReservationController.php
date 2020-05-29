<?php

namespace App\Controller\Chene;

use App\Form\Chene\CreateReservationFlow;
use App\Controller\BaseController;
use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\ReservationJeu;
use App\Entity\General\Conversation;
use App\Entity\General\Message;
use App\Form\Chene\ReservationJeuType;
use App\Repository\Chene\ReservationJeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @route("/chene/location", name="chene.location.") 
 */
class ReservationController extends BaseController {

    protected function getThemeCourant(): string {
        return "Chêne";
    }

    protected function getMenuCourant(): string {
        return "Reservation";
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

    private function creerMessage(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(true);
        $message->setVu(false);
        $message->setVuGourou(true);

        return $message;
    }

    private function creerConversation(ReservationJeu $reservation) {
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        $conversation = new Conversation();
        $conversation->setLienReservation($reservation);
        $conversation->setUser($this->getUser());
        $conversation->setSujet("Location du Jeu en Chêne " . $reservation->getJeu()->getNom());
        $conversation->setCreeParGourou(true);

        $this->em->persist($conversation);

        $message1 = $this->creerMessage($reservation, $conversation);
        $message1->setTexte("Bonjour, \n"
                . "Merci d'avoir loué le Jeu en Chêne " . $reservation->getJeu()->getNom() . ". "
                . "Nous espèrons qu'il vous plaira.\n" );
        $this->em->persist($message1);

        if ($reservation->getRetraitDomicile()) {
            $message2 = $this->creerMessage($reservation, $conversation);
            $message2->setTexte("Vous avez choisi le retrait à Saint Philbert de Grand Lieu.\n\n"
                    . "Voici l'adresse pour le retrait :\n"
                    . "24 Ter rue des Guittières \n"
                    . "44 310 SAINT PHILBERT DE GRAND LIEU \n\n"
                    . "En cas d'absence de notre part, le Jeu en Chêne avec les instructions sont à retirer dans la boite en chêne située près du tas de bois. C'est immanquable ! \n\n"
                    . "Le code du cadenas est 8181.\n"
                    . "Déposez à la place vos babioles si vous en avez...");

            $this->em->persist($message2);

            $message3 = $this->creerMessage($reservation, $conversation);
            $message3->setTexte("Vous avez choisi d'effectuer le retrait le " . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRetrait()->getTimestamp()) . ".\n"
                    . "En cas de soucis de dernière minute, vous pouvez nous contacter au 06 76 49 57 23.\n");

            $this->em->persist($message3);
        } else {
            $message2 = $this->creerMessage($reservation, $conversation);
            $message2->setTexte("Vous avez choisi le retrait sur rendez-vous le " . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRetrait()->getTimestamp()) . " .\n\n"
                    . "Votre proposition :\n"
                    . $reservation->getLieuRDV() . "\n\n"
                    . "Nous vous confirmerons ci-dessous dans un prochain message ce rendez-vous...\n"
                    . "Si le délai est court, contacter-nous au 06 76 49 57 23.");

            $this->em->persist($message2);
        }
        
        $message4 = $this->creerMessage($reservation, $conversation);
        $message4->setTexte("Pour toute question liée à cette location, n'hésitez pas à écrire votre demande ci-dessous.");
        $this->em->persist($message4);
    }

    private function effectuerLocation(ReservationJeu $reservation) {
        // status de Possession
        if ($reservation->getABabiole())
            $reservation->setPossessionBabiole(0);
        else if ($reservation->getAPeutEtreBabiole())
            $reservation->setPossessionBabiole(1);
        else if ($reservation->getAPasAssezBabiole())
            $reservation->setPossessionBabiole(2);
        else
            $reservation->setPossessionBabiole(3);

        $this->creerConversation($reservation);
    }

    /**
     * 
     * @route("/{slug}-{id}", name="new", methods={"GET","POST"})  
     * @return Response
     */
    public function reserver(CreateReservationFlow $flow, JeuEnChene $jeuEnChene, string $slug) {
        $reservation = new ReservationJeu();
        $reservation->setDateDemande(new \DateTime('now'));
        $reservation->setJeu($jeuEnChene);
        $reservation->setUser($this->getUser());

        $flow->bind($reservation);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));

            if ($flow->nextStep()) {
                $form = $flow->createForm();
            } else {
                // flow finished
                $this->effectuerLocation($reservation);
                $this->em->persist($reservation);
                //$reservation->getJeu()->setDisponible(false);
                $this->em->persist($reservation->getJeu());
                $this->em->flush();

                $flow->reset(); // remove step data from the session

                return $this->redirectToRoute('chene.location.confirmation', [
                            'id' => $reservation->getId()
                ]);
            }
        }

        return $this->monRender('chene/reservation/reservation.html.twig', [
                    'form' => $form->createView(),
                    'jeu' => $jeuEnChene,
                    'reservation' => $reservation,
                    'flow' => $flow,
        ]);
    }

    /**
     * 
     * @route("/voir/{id}", name="show")  
     * @return Response
     */
    public function show(ReservationJeu $reservation) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                return $this->redirect($this->generateUrl('home'));
            }
        } else
            return $this->redirect($this->generateUrl('home'));

        return $this->monRender('chene/reservation/show.html.twig', [
                    'reservation' => $reservation,
        ]);
    }

    /**
     * 
     * @route("/confirmation/{id}", name="confirmation")  
     * @return Response
     */
    public function confirmation(ReservationJeu $reservation) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                return $this->redirect($this->generateUrl('home'));
            }
        } else
            return $this->redirect($this->generateUrl('home'));


        return $this->monRender('chene/reservation/confirmation.html.twig', [
                    'reservation' => $reservation,
        ]);
    }

}
