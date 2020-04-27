<?php

namespace App\Controller\Admin\Chene;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

    /**
    * @route("/admin/chene") 
    * @IsGranted("ROLE_ADMIN")
    */
class AdminCheneController extends BaseController
{
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminChene";
    }
        
    /**
     * @route("/", name="admin.chene.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->monRender('admin/chene/home.html.twig');
    }
    
}


