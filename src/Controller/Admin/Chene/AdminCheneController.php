<?php

namespace App\Controller\Admin\Chene;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

    /**
    * @route("/admin/chene") 
    * @IsGranted("ROLE_ADMIN")
    */
class AdminCheneController extends AbstractController
{
    
    /**
     * @var string
     */
    private $menuCourant = "AdminChene";
    
    /**
     * @var string
     */
    private $themeCourant = "Chêne";
        
    /**
     * @route("/", name="admin.chene.home")  
     * @return Response
     */
    public function home( ) : Response
    {
        return $this->render('admin/chene/home.html.twig', [
            'menuCourant' => $this->menuCourant,
            'themeCourant' => $this->themeCourant
        ]);
    }
    
}


