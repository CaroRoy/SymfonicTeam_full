<?php 

namespace App\Controller\User\Reply;

use App\Entity\ReplyEventUser;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserInterestedListController extends AbstractController {
    /**
     * Affiche la liste des séances par lesquelles l'utilisateur est intéressé
     * 
     * @Route("/mes-favoris", name="user_interested_list")
     */
    public function list(ReplyEventUserRepository $replyEventUserRepository) : Response {
        $user = $this->getUser();
        $replys = $replyEventUserRepository->findBy(['user' => $user, 'replyType' => ReplyEventUser::INTERESTED]);

        return $this->render("user/interested_event_list.html.twig",['replys' => $replys]);
    }
}