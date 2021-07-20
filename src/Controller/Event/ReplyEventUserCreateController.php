<?php

namespace App\Controller\Event;

use App\Entity\ReplyEventUser;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReplyEventUserCreateController extends AbstractController {
    /**
     * @Route("seance-{id}/participation", name="event_reply")
     */
    public function participate(int $id, EventRepository $eventRepository, Request $request, EntityManagerInterface $em) {
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

        $reply = new ReplyEventUser;
        $reply->setUser($user);
        $reply->setEvent($event);
        $em->persist($reply);
        $em->flush();

        $this->addFlash('success','Ton inscription à cette séance a bien été enregistrée');

        return $this->redirectToRoute('event_list');

        return $this->render('event_list.html.twig',['id' => $id]);
    }
}
