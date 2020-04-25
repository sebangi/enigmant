<?php

namespace App\Controller\Admin\Chasse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @route("/admin/chasse") 
 * @IsGranted("ROLE_ADMIN")
 */
class AdminChasseController extends AbstractController {

    /**
     * @var string
     */
    private $menuCourant = "AdminChasse";

    /**
     * @var string
     */
    private $themeCourant = "Chasse";

    /**
     * @route("/", name="admin.chasse.home")  
     * @return Response
     */
    public function home(): Response {
        return $this->render('admin/chasse/home.html.twig', [
                    'menuCourant' => $this->menuCourant,
                    'themeCourant' => $this->themeCourant
        ]);
    }

}
