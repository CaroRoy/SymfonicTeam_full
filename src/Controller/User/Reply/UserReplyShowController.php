<?php 

namespace App\Controller\User\Reply;

use App\Repository\ReplyEventUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserReplyShowController extends AbstractController {
    /**
     * @Route("mes-participations/details/seance-{id}", name="user_reply_show")
     */
    public function show(int $id, ReplyEventUserRepository $replyEventUserRepository) : Response {
        $user = $this->getUser();
        $reply = $replyEventUserRepository->find($id);

        if (!$reply) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_reply_list');
        }

        if($reply->getUser() !== $user) {
            $this->addFlash('danger','Erreur : cette action est impossible');
            return $this->redirectToRoute('user_reply_list');
        }

        $event = $reply->getEvent();

        return $this->render("user/reply_show.html.twig",['reply' => $reply, 'event' => $event]);
    }
}