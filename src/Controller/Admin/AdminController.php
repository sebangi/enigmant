<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{    
    /**
     * @var string
     */
    private $menu_courant = "User";
    
    /**
     * @var string
     */
    private $theme_courant = "Chêne";
    
    /**
     * @Route("/", name="admin.home", methods={"GET"})
     */
    public function home(): Response
    {
        return $this->render('admin/adminHome.html.twig', [
            'menu_courant' => 'AdminHome',
            'theme_courant' => 'Chêne'
        ]);
    }
    
}
