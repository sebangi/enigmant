<?php

namespace App\Controller\Admin\General;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{    
    /**
     * @var string
     */
    private $menuCourant = "AdminHome";
    
    /**
     * @var string
     */
    private $themeCourant = "Général";
    
    /**
     * @Route("/", name="admin.home", methods={"GET"})
     */
    public function home(): Response
    {
        return $this->render('admin/general/adminHome.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant            
        ]);
    }
}
