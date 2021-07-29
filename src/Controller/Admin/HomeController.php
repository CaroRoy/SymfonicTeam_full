<?php 

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("admin/accueil", name="admin_home")
     */
    public function home() {
        return $this->render('admin/home.html.twig');
    }
}