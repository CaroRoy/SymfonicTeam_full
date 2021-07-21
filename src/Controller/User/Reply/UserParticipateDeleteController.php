<?php 

namespace App\Controller\User\Reply;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserParticipateDeleteController extends AbstractController {
    /**
     * @Route("/mes-participations/supprimer/seance-{id}", name="user_reply_delete")
     */
    public function delete(int $id, ReplyEventUserRepository $replyEventUserRepository, EntityManagerInterface $em) : RedirectResponse {
        $reply = $replyEventUserRepository->find($id);
        $user = $this->getUser();

        if (!$reply) {
            $this->addFlash('danger','Tu n\'es pas inscrit(e) à cette séance');
            return $this->redirectToRoute('user_reply_list');
        }

        if($reply->getUser() !== $user) {
            $this->addFlash('danger','Erreur : cette action est impossible');
            return $this->redirectToRoute('user_reply_list');
        }

        $em->remove($reply);
        $em->flush();

        $this->addFlash('success','Tu n\'es plus inscrit(e) à cette séance');
        return $this->redirectToRoute('user_reply_list');
    }
}