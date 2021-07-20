<?php 

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserShowController extends AbstractController
{
    /**
     * @Route("admin/details/utilisateur-{id}", name="admin_user_show")
     */
    public function show(int $id, UserRepository $userRepository) {
        $user = $userRepository->find($id);
        $events = $user->getCreatedEvents();
    
        return $this->render('admin/user_show.html.twig',['user' => $user, 'events' => $events]);
    }
}