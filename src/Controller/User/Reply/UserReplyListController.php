<?php 

namespace App\Controller\User\Reply;

use App\Repository\ReplyEventUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserReplyListController extends AbstractController {
    /**
     * @Route("/mes-participations", name="user_reply_list")
     */
    public function list(ReplyEventUserRepository $replyEventUserRepository) : Response {
        $user = $this->getUser();
        $replys = $replyEventUserRepository->findBy(['user' => $user]);

        return $this->render("user/reply_list.html.twig",['replys' => $replys]);
    }
}