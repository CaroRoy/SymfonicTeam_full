<?php

namespace App\Controller\Event;

use App\Entity\ReplyEventUser;
use App\MesServices\EmailService;
use App\Repository\EventRepository;
use App\Repository\ReplyEventUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReplyEventUserCreateController extends AbstractController {
    /**
     * @Route("seance-{id}/participation", name="event_reply")
     */
    public function participate(int $id, EventRepository $eventRepository, ReplyEventUserRepository $replyEventUserRepository,EntityManagerInterface $em, EmailService $emailService) {
        $user = $this->getUser();
        $event = $eventRepository->find($id);
        $replyEventUser = $replyEventUserRepository->findOneBy(['user' => $user, 'event' => $event]);

        if(!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        if($event->getUser() === $user) {
            $this->addFlash('warning','Tu ne peux pas répondre à ta propre séance');
            return $this->redirectToRoute('event_list');
        }

        if ($event->getReplyEventUsers()->contains($replyEventUser)) {
            $this->addFlash('warning','Tu es déjà inscrit(e) à cette séance');
            return $this->redirectToRoute('event_list');
        }

        $reply = new ReplyEventUser;
        $reply->setUser($user);
        $reply->setEvent($event);
        $em->persist($reply);
        $em->flush();

        $emailService->sendNotificationNewParticipant($event, $event->getUser());
        $this->addFlash('success','Ton inscription à cette séance a bien été enregistrée');
        return $this->redirectToRoute('event_list');

        return $this->render('event_list.html.twig',['id' => $id]);
    }
}
