<?php 

namespace App\Controller\Admin\User;

use App\Entity\ReplyEventUser;
use App\Repository\UserRepository;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserShowController extends AbstractController
{
    /**
     * @Route("admin/details/utilisateur-{id}", name="admin_user_show")
     */
    public function show(int $id, UserRepository $userRepository, ReplyEventUserRepository $replyEventUserRepository) {
        $user = $userRepository->find($id);
        $events = $user->getCreatedEvents();
        $replys = $user->getReplyEventUsers();
        $participations = $replyEventUserRepository->findBy(['user' => $user, 'replyType' => ReplyEventUser::OK]);
        $favoris = $replyEventUserRepository->findBy(['user' => $user, 'replyType' => ReplyEventUser::INTERESTED]);

        return $this->render('admin/user_show.html.twig',['user' => $user, 'events' => $events, 'replys' => $replys, 'participations' => $participations, 'favoris' => $favoris]);
    }
}