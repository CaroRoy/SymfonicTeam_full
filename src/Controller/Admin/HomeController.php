<?php 

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Affiche l'accueil de l'espace admin
     * 
     * @Route("admin/accueil", name="admin_home")
     */
    public function home() {
        return $this->render('admin/home.html.twig');
    }
}