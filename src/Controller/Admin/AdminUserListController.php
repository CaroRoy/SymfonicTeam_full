<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserListController extends AbstractController {
    /**
     * @Route("admin/utilisateurs", name="admin_user_list")
     */
    public function list(UserRepository $userRepository): Response {
        $users = $userRepository->findAll();

        return $this->render("admin/user_list.html.twig",['users' => $users]);
    }
}