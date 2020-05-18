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

    /**
     * 
     * @route("/{slug}-{id}", name="chene.location", methods={"GET","POST"})  
     * @return Response
     */
    public function reserver(CreateReservationFlow $flow, JeuEnChene $jeuEnChene, string $slug) {
        $reservation = new ReservationJeu();
        $reservation->setDateDemande(new \DateTime('now'));
        $reservation->setDateRetrait(new \DateTime('now'));
        $reservation->setJeu($jeuEnChene);
        $reservation->setUser($this->getUser());

        $flow->bind($reservation);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

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
                    'flow' => $flow,
        ]);
    }


}
