<?php 

namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PrivateSpaceController extends AbstractController {
    /**
     * @Route("mon-espace", name="user_private_space")
     */
    public function showSpace(): Response {
        return $this->render("user/private_space.html.twig");
    }
}