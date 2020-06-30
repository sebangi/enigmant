<?php

namespace App\Controller\Chene;

use App\Form\Chene\CreateReservationFlow;
use App\Form\Chene\CreateRetourFlow;
use App\Controller\BaseController;
use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\ReservationJeu;
use App\Entity\General\Conversation;
use App\Entity\General\Message;
use App\Form\Chene\ReservationJeuType;
use App\Repository\General\ThemeRepository;
use App\Entity\General\Theme;
use App\Repository\Chene\ReservationJeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
     *
     * @var MailerInterface 
     */
    private $mailer;

    public function __construct(EntityManagerInterface $em, ReservationJeuRepository $repository, MailerInterface $mailer) {
        parent::__construct($em);
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    /**
     * @route("/", name="index")  
     * @var Request $Request
     * @return Response
     */
    public function index(Request $Requete): Response {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $reservation = $this->repository->findAllSelonUser($this->getUser());
            return $this->monRender('chene/reservation/index.html.twig', [
                        'reservations' => $reservation,
            ]);
        } else
            return $this->redirect($this->generateUrl('home'));
    }    

    private function creerMessageRetrait(ReservationJeu $reservation, Conversation $conversation) {
        if ($reservation->getRetraitDomicile()) {
            $message = $this->creerMessage($conversation);
            $message->setTexte("Vous avez choisi d'effectuer le retrait à Saint Philbert de Grand Lieu, le " . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRetrait()->getTimestamp()) . ".\n"
                    . "Nous vous confirmerons le retrait ci-dessous dans un prochain message dès que le Jeu sera prêt.\n"
                    . "En cas de délai court, vous pouvez nous contacter au 06 76 49 57 23.\n");

            $this->em->persist($message);
        } else {
            $message = $this->creerMessage($conversation);
            $message->setTexte("RETRAIT DU JEU EN CHÊNE \n\n"
                    . "Vous avez choisi le retrait sur rendez-vous le " . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRetrait()->getTimestamp()) . " .\n\n"
                    . "Votre proposition :\n"
                    . $reservation->getLieuRDV() . "\n\n"
                    . "Nous vous confirmerons ci-dessous dans un prochain message ce rendez-vous...\n"
                    . "Si le délai est court, contactez-nous au 06 76 49 57 23.");

            $this->em->persist($message);
        }
    }

    private function creerMessageModificationLieuRetrait(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        if ($reservation->getRetraitDomicile()) {
            $message->setTexte("Message automatique.\n"
                    . "Modification du lieu de retrait : retrait à Saint Philbert de Grand Lieu.");
        } else {
            $message->setTexte("Message automatique.\n"
                    . "Modification du lieu de retrait : retrait sur rendez-vous.\n"
                    . "Proposition de lieu : \n"
                    . $reservation->getLieuRDV());
        }

        $this->em->persist($message);
        $this->creerMessageRetrait($reservation, $conversation);
    }

    private function creerMessageModificationDateRetrait(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        $message->setTexte("Message automatique.\n"
                . "Modification de la date de retrait : "
                . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRetrait()->getTimestamp()));

        $this->em->persist($message);
    }

    private function creerMessageRetour(ReservationJeu $reservation, Conversation $conversation) {
        if ($reservation->getRetourDomicile()) {
            $message = $this->creerMessage($conversation);
            $message->setTexte("RETOUR DU JEU EN CHÊNE \n\n"
                    . "Vous avez choisi le retour à Saint Philbert de Grand Lieu.\n"
                    . "Voici l'adresse pour le retrait :\n"
                    . "24 Ter rue des Guittières \n"
                    . "44 310 SAINT PHILBERT DE GRAND LIEU \n\n"
                    . "En cas d'absence de notre part, Déposez le Jeu en Chêne dans la boîte en chêne située près du tas de bois. C'est immanquable ! \n\n"
                    . "Le code du cadenas est 8181.\n"
                    . "Déposez à la place vos babioles si vous ne l'aviez pas fait lors du retrait...");

            $this->em->persist($message);
        } else {
            $message = $this->creerMessage($conversation);
            $message->setTexte("RETOUR DU JEU EN CHÊNE \n\n"
                    . "Vous avez choisi le retour sur rendez-vous le " . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRendu()->getTimestamp()) . " .\n\n"
                    . "Votre proposition :\n"
                    . $reservation->getLieuRetourRDV() . "\n\n"
                    . "Nous vous confirmerons ci-dessous dans un prochain message ce rendez-vous...\n"
                    . "Si le délai est court, contactez-nous au 06 76 49 57 23.");

            $this->em->persist($message);
        }
    }

    private function creerMessageModificationDateRetour(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        $message->setTexte("Message automatique.\n"
                . "Modification de la date de retour : "
                . strftime("%A %d %B %Y à %H:%M", $reservation->getDateRendu()->getTimestamp()));

        $this->em->persist($message);
    }

    private function creerMessageModificationLieuRetour(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        if ($reservation->getRetourDomicile()) {
            $message->setTexte("Message automatique.\n"
                    . "Modification du lieu de retour : retour à Saint Philbert de Grand Lieu.");
        } else {
            $message->setTexte("Message automatique.\n"
                    . "Modification du lieu de retour : retour sur rendez-vous.\n"
                    . "Proposition de lieu : \n"
                    . $reservation->getLieuRetourRDV());
        }

        $this->em->persist($message);
        $this->creerMessageRetour($reservation, $conversation);
    }

    private function creerMessageAvisDonne(ReservationJeu $reservation, Conversation $conversation) {
        $message = new Message();
        $message->setConversation($conversation);
        $message->setMessageGourou(true);
        $message->setVu(false);
        $message->setVuGourou(true);
        $message->setTexte("Merci beaucoup pour votre avis.\n\n"
                . "À très bientôt.\n"
                . "Grand Gourou en Chêne Seb");

        $this->em->persist($message);
    }

    private function creerConversation(ReservationJeu $reservation) {
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        $conversation = new Conversation();
        $conversation->setLienReservation($reservation);
        $conversation->setUser($this->getUser());
        $conversation->setSujet("Location du Jeu en Chêne " . $reservation->getJeu()->getNom());
        $conversation->setCreeParGourou(true);
        $theme = $this->getDoctrine()->getRepository(Theme::class)->findOneBy(["nom" => "chêne"]);
        $conversation->setTheme($theme);

        $this->em->persist($conversation);

        $message1 = $this->creerMessage($conversation);
        $message1->setVuGourou(false);
        $message1->setTexte("Bonjour " . $reservation->getUser()->getUsername() . ", \n\n"
                . "Vous avez loué le Jeu en Chêne " . $reservation->getJeu()->getNom() . ".\n"
                . "Nous préparons au plus vite le Jeu en Chêne.\n\n"
                . "Nous espèrons vivement qu'il vous plaira !\n");
        $this->em->persist($message1);

        $this->creerMessageRetrait($reservation, $conversation, false);

        $message2 = $this->creerMessage($conversation);
        $message2->setTexte("Pour toute question liée à cette location, n'hésitez pas à écrire votre demande ci-dessous.");
        $this->em->persist($message2);
    }

    private function envoyerMailLocation(ReservationJeu $reservation) {
        $email1 = (new TemplatedEmail())
                ->from($this->getParameter('MAIL_FROM_GOUROU'))
                ->subject("Location du Jeu en Chêne " . $reservation->getJeu()->getNom())
                ->to($reservation->getUser()->getEmail())
                ->htmlTemplate('chene/reservation/_email.html.twig')
                ->context([
                    'reservation' => $reservation,
                ])
        ;

        $this->mailer->send($email1);
        
        $email2 = (new TemplatedEmail())
                ->from($this->getParameter('MAIL_FROM_GOUROU'))
                ->subject("ENIGMANT CHÊNE : Location du Jeu en Chêne " . $reservation->getJeu()->getNom())
                ->to($this->getParameter('MAIL_DESTINATAIRE_GOUROU'))
                ->htmlTemplate('chene/reservation/_emailGourou.html.twig')
                ->context([
                    'reservation' => $reservation,
                ])
        ;

        $this->mailer->send($email2);
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
     * @route("/{slug}-{id}", name="new", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})  
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
                $reservation->getJeu()->setDisponible(false);
                $this->em->persist($reservation->getJeu());
                $this->em->flush();
                
                $reservation2 = $this->repository->findAvecConversationEtJeu($reservation->getId());
                if ( count($reservation2) != 0 )
                    $this->envoyerMailLocation($reservation2[0]);

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
     * @route("/retourner/{slug}-{id}", name="retourner", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})  
     * @return Response
     */
    public function retourner(CreateRetourFlow $flow, ReservationJeu $reservation, string $slug) {
        if ($reservation->getSlug() !== $slug)
            return $this->redirectToRoute('home', [], 301);
        $reservation->setDateRendu(new \DateTime('now'));

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
                $this->creerMessageRetour($reservation, $reservation->getConversation());

                $this->em->flush();
                $flow->reset(); // remove step data from the session

                return $this->redirectToRoute('chene.location.show', [
                            'id' => $reservation->getId(),
                            'slug' => $reservation->getSlug(),
                            '_fragment' => 'retour'
                ]);
            }
        }

        return $this->monRender('chene/reservation/retour.html.twig', [
                    'form' => $form->createView(),
                    'jeu' => $reservation->getJeu(),
                    'reservation' => $reservation,
                    'flow' => $flow,
        ]);
    }

    /**
     * @Route("/edit/{champ}/{slug}-{id}", name="edit.champ", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})
     */
    public function editChamp(Request $request, ReservationJeu $reservation, string $slug, $champ): Response {
        if ($reservation->getSlug() !== $slug)
            return $this->redirectToRoute('home', [], 301);
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createForm(ReservationJeuType::class, $reservation,
                ['champ' => $champ, 'noteValue' => $reservation->getNote()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('cancel')->isClicked()) {
                if ($champ == "dateRetrait") {
                    $this->creerMessageModificationDateRetrait($reservation, $reservation->getConversation());
                } else if ($champ == "lieuRetrait") {
                    $this->creerMessageModificationLieuRetrait($reservation, $reservation->getConversation());
                } else if ($champ == "dateRendu") {
                    $this->creerMessageModificationDateRetour($reservation, $reservation->getConversation());
                } else if ($champ == "lieuRetour") {
                    $this->creerMessageModificationLieuRetour($reservation, $reservation->getConversation());
                } else if ($champ == "avis") {
                    $reservation->setNote($request->get("reservation_jeu")["note"]);

                    if ((!$reservation->getAvisDonne() ) &&
                            ( ( $reservation->getNote() != -1 ) ||
                            (!is_null($reservation->getAvisPublic()) ) ||
                            (!is_null($reservation->getAvisPriveDifficulte()) ) ||
                            (!is_null($reservation->getAvisPriveTechnique()))
                            )) {
                        $reservation->setEtat(6);
                        $reservation->setAvisDonne(true);
                        $this->creerMessageAvisDonne($reservation, $reservation->getConversation());
                        $this->addFlash('success', 'Nous avons bien reçu votre avis. Merci !');
                    }
                } else if ($champ == "babioles") {
                    $this->addFlash('success', 'La liste des babioles a été modifiée.');
                }

                foreach ($reservation->getBabioles() as $babiole) {
                    $babiole->setReservationJeu($reservation);
                }


                $this->em->flush();
            }

            return $this->redirectToRoute('chene.location.show', [
                        'id' => $reservation->getId(),
                        'slug' => $reservation->getSlug()
            ]);
        }

        return $this->monRender('chene/reservation/edit_champ.html.twig', [
                    'reservation' => $reservation,
                    'champ' => $champ,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trouve/{slug}-{id}", name="trouve", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})
     */
    public function trouve(Request $request, ReservationJeu $reservation, string $slug): Response {
        if ($reservation->getSlug() !== $slug)
            return $this->redirectToRoute('home', [], 301);

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));
        }

        if ($reservation->getEtat() != 2)
            return $this->redirectToRoute('chene.location.show', [
                        'id' => $reservation->getId(),
                        'slug' => $reservation->getSlug()
            ]);

        $reservation->setEtat(3);
        $reservation->setDateRendu(new \DateTime('now'));
        $this->em->persist($reservation);

        $message = new Message();
        $message->setConversation($reservation->getConversation());
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        $message->setTexte("Message automatique.\nJ'ai trouvé le médaillon.");
        $this->em->persist($message);

        $this->em->flush();

        return $this->monRender('chene/reservation/trouve.html.twig', [
                    'jeu' => $reservation->getJeu(),
                    'reservation' => $reservation
        ]);
    }

    /**
     * @Route("/rendre/{slug}-{id}", name="rendre", methods={"GET","POST"}, requirements={"slug": "[a-z0-9\-]*"})
     */
    public function rendre(Request $request, ReservationJeu $reservation, string $slug): Response {
        if ($reservation->getSlug() !== $slug)
            return $this->redirectToRoute('home', [], 301);
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('home'));
        }

        $reservation->setEtat(3);
        $reservation->setDateRendu(new \DateTime('now'));
        $this->em->persist($reservation);

        $message = new Message();
        $message->setConversation($reservation->getConversation());
        $message->setMessageGourou(false);
        $message->setVu(true);
        $message->setVuGourou(false);
        $message->setTexte("Message automatique.\nJe souhaite retourner le Jeu en Chêne.");

        $this->em->persist($message);

        $this->em->flush();

        return $this->redirectToRoute('chene.location.retourner', [
                    'id' => $reservation->getId(),
                    'slug' => $reservation->getSlug()
        ]);
    }

    /**
     * 
     * @route("/voir/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})  
     * @return Response
     */
    public function show(ReservationJeu $reservation, string $slug) {
        if ($reservation->getSlug() !== $slug)
            return $this->redirectToRoute('home', [], 301);

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ($reservation->getUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirect($this->generateUrl('home'));
                }
            } else
                return $this->redirect($this->generateUrl('app_login'));
        }

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
