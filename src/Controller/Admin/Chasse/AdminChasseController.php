<?php

namespace App\Controller\Admin\Chasse;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @route("/admin/chasse") 
 * @IsGranted("ROLE_ADMIN")
 */
class AdminChasseController extends BaseController {

    protected function getThemeCourant() : string
    {
        return "Chasse";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminChasse";
    }

    /**
     * @route("/", name="admin.chasse.home")  
     * @return Response
     */
    public function home(): Response {
        return $this->monRender('admin/chasse/home.html.twig');
    }

}
