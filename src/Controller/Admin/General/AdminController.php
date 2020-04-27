<?php

namespace App\Controller\Admin\General;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends BaseController
{    
    protected function getThemeCourant() : string
    {
        return "Général";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminHome";
    }
    
    /**
     * @Route("/", name="admin.home", methods={"GET"})
     */
    public function home(): Response
    {
        return $this->monRender('admin/general/adminHome.html.twig');
    }
}
