<?php

namespace App\Controller\Chene;

use App\Controller\BaseController;
use App\Entity\Chene\JeuEnChene;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
     * 
     * @route("/{slug}-{id}-{etape}", name="chene.location", requirements={"slug": "[a-z0-9\-]*"})  
     * @param JeuEnChene $jeuEnChene
     * @param string $slug
     * @return Response
     */
    public function reserver(JeuEnChene $jeuEnChene, string $slug, $etape): Response {
        if ($jeuEnChene->getSlug() !== $slug)
            return $this->redirectToRoute('JeuEnChene.show', [
                        'id' => $jeuEnChene->getId(),
                        'slug' => $jeuEnChene->getSlug()
                            ], 301);
        dump($etape);
        if (!$jeuEnChene->getDisponible()) {
            $this->addFlash('danger', "Désolé, le Jeu en Chêne n'est plus disponible.");

            return $this->redirectToRoute('JeuEnChene.show', [
                        'id' => $jeuEnChene->getId(),
                        'slug' => $jeuEnChene->getSlug()
            ]);
        }

        return $this->monRender('chene/reservation/etape' . $etape . '.html.twig', [
                    "jeu" => $jeuEnChene
        ]);
    }

}
