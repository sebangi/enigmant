<?php

namespace App\Controller\Chene;

use App\Repository\General\ActualiteRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @route("/chene") 
 */
class CheneController extends BaseController {

    protected function getThemeCourant(): string {
        return "Chêne";
    }

    protected function getMenuCourant(): string {
        return "EnigmesEnChene";
    }

    /**
     * 
     * @route("/", name="chene.home")  
     * @param PaginatorInterface $paginator
     * @param Request $Requete
     * @param JeuEnCheneRepository $repository
     * @return Response
     */
    public function home(PaginatorInterface $paginator, Request $Requete, ActualiteRepository $repository): Response {
        $actualites = $paginator->paginate(
                $repository->findDernieresActialites($this->getThemeCourant()),
                $Requete->query->getInt('page', 1),
                4
        );

        return $this->monRender('chene/home.html.twig', [
                    'pagination' => $paginator,
                    'actualites' => $actualites
        ]);
    }

}
