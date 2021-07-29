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

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Tu dois te connecter pour accéder à ton espace');
            return $this->redirectToRoute('app_login');
        }
        return $this->render("user/private_space.html.twig");
    }
}