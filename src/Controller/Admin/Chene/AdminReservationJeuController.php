<?php

namespace App\Controller\Admin\Chene;

use App\Entity\Chene\ReservationJeu;
use App\Form\Chene\ReservationJeuType;
use App\Repository\Chene\ReservationJeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/chene/reservation")
 */
class AdminReservationJeuController extends AbstractController
{
    /**
     * @var string
     */
    private $menuCourant = "AdminReservation";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
    
    /**
     * @Route("/", name="admin.chene.reservation.index", methods={"GET"})
     */
    public function index(ReservationJeuRepository $reservationJeuRepository): Response
    {
        return $this->render('admin/chene/reservation/index.html.twig', [
            'menuCourant' => 'AdminReservationJeu',
            'themeCourant' => 'Chêne',
            'reservations' => $reservationJeuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.chene.reservation.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reservation = new ReservationJeu();        
        $reservation->setDateDemande( new \DateTime('now') );
        $form = $this->createForm(ReservationJeuType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation ajoutée avec succès.');
            
            return $this->redirectToRoute('admin.chene.reservation.index');
        }

        return $this->render('admin/chene/reservation/new.html.twig', [
            'menuCourant' => 'AdminReservationJeu',
            'themeCourant' => 'Chêne',
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.chene.reservation.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReservationJeu $reservation): Response
    {
        $form = $this->createForm(ReservationJeuType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Réservation créée avec succès.');
            
            return $this->redirectToRoute('admin.chene.reservation.index');
        }

        return $this->render('admin/chene/reservation/edit.html.twig', [
            'menuCourant' => 'AdminReservationJeu',
            'themeCourant' => 'Chêne',
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.chene.reservation.delete", methods={"DELETE"})
     */
    public function delete(Request $request, ReservationJeu $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'Réservation supprimée avec succès.');            
        }

        return $this->redirectToRoute('admin.chene.reservation.index');
    }
}
