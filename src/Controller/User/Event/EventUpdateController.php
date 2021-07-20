<?php

namespace App\Controller\User\Event;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EventUpdateController extends AbstractController {
    /**
     * @Route("modifier/seance-{id}", name="event_update")
     */
    public function create(int $id,Request $request, EntityManagerInterface $em, EventRepository $eventRepository) : Response {
        $event = $eventRepository->find($id);
        $user = $this->getUser();

        if(!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        // GESTION DES AUTORISATIONS
        if($event->getUser() !== $user) {
            $this->addFlash('danger','Tu n\'es pas autorisé à modifier cette séance');
            return $this->redirectToRoute('event_list');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Event $event */
            $event = $form->getData();

            $em->flush();

            $this->addFlash('success','Ta séance a bien été modifiée');

            return $this->redirectToRoute('user_event_list');
        }

        return $this->render('event/update.html.twig',['form' => $form->createView(), 'event' => $event]);
    }
}