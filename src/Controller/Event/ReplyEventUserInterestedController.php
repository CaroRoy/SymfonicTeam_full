<?php

namespace App\Controller\Event;

use App\Entity\ReplyEventUser;
use App\MyServices\EmailService;
use App\Repository\EventRepository;
use App\Repository\ReplyEventUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReplyEventUserInterestedController extends AbstractController {
    /**
     * Enregistre l'intérêt de l'utilisateur pour une séance
     * 
     * @Route("seance-{id}/favoris", name="event_reply_interested")
     */
    public function interest(int $id, EventRepository $eventRepository, ReplyEventUserRepository $replyEventUserRepository,EntityManagerInterface $em, EmailService $emailService) {
        $user = $this->getUser();
        $event = $eventRepository->find($id);

        if(!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        if($event->getUser() === $user) {
            $this->addFlash('warning','Tu ne peux pas répondre à ta propre séance');
            return $this->redirectToRoute('event_list');
        }

        $replyEventUser = $replyEventUserRepository->findOneBy(['user' => $user, 'event' => $event]);
        if ($event->getReplyEventUsers()->contains($replyEventUser)) {
            if ($replyEventUser->getReplyType() === $replyEventUser::OK) {
                $this->addFlash('warning','Tu es déjà inscrit(e) à cette séance');
                return $this->redirectToRoute('event_list');
            }
            if($replyEventUser->getReplyType() === $replyEventUser::INTERESTED) {
                $this->addFlash('warning','Cette séance est déjà sauvegardée');
                return $this->redirectToRoute('event_list');
            }
        }

        $reply = new ReplyEventUser;
        $reply->setUser($user);
        $reply->setEvent($event);
        $reply->setReplyType($reply::INTERESTED);

        $em->persist($reply);
        $em->flush();

        $this->addFlash('success','Cette séance a bien été enregistrée dans ta section "Les séances qui m\'intéressent"');
        return $this->redirectToRoute('event_list');

        return $this->render('event_list.html.twig',['id' => $id]);
    }
}
