<?php 

namespace App\Controller\User\Reply;

use App\Entity\ReplyEventUser;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserParticipateListController extends AbstractController {
    /**
     * @Route("/mes-participations", name="user_participate_list")
     */
    public function list(ReplyEventUserRepository $replyEventUserRepository) : Response {
        $user = $this->getUser();
        $replys = $replyEventUserRepository->findBy(['user' => $user, 'replyType' => ReplyEventUser::OK]);

        return $this->render("user/participate_event_list.html.twig",['replys' => $replys]);
    }
}