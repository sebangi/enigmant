<?php

namespace App\Controller\Chene;

use App\Form\Chene\CreateReservationFlow;
use App\Controller\BaseController;
use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\ReservationJeu;
use App\Form\Chene\ReservationJeuType;
use App\Repository\Chene\ReservationJeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @route("/chene/location" ) 
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
     * 
     * @route("/{slug}-{id}", name="chene.location", methods={"GET","POST"})  
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

            if ( $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                if ( $reservation->getUser()->getId() != $this->getUser()->getId())
                {
                    return $this->redirect($this->generateUrl('home')); 
                }
            }
            else
                return $this->redirect($this->generateUrl('home')); 
                
            if ($flow->nextStep()) {
                $form = $flow->createForm();
            } else {
                // flow finished
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);
                $em->flush();

                $flow->reset(); // remove step data from the session

                return $this->redirect($this->generateUrl('home')); // redirect when done
            }
        }

        return $this->monRender('chene/reservation/reservation.html.twig', [
                    'form' => $form->createView(),
                    'jeu' => $jeuEnChene,
                    'reservation' => $reservation,
                    'flow' => $flow,
        ]);
    }


}
