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
            'menu_courant' => 'AdminHome'
        ]);
    }
    
    /**
     * @Route("/", name="admin.chene", methods={"GET"})
     */
    public function chene(): Response
    {
        return $this->render('admin/chene/adminBaseChene.html.twig', [
            'menu_courant' => 'AdminChene'
        ]);
    }
    
    /**
     * @Route("/", name="admin.theme", methods={"GET"})
     */
    public function theme(): Response
    {
        return $this->render('admin/theme/index.html.twig', [
            'menu_courant' => 'AdminTheme'
        ]);
    }
    
}
