<?php 

namespace App\Controller\User;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventShowController extends AbstractController {
    /**
     * @Route("/details/seance-{id}", name="user_event_show")
     */
    public function show(int $id, EventRepository $eventRepository) : Response {
        $user = $this->getUser();
        $event = $eventRepository->find($id);

        if (!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        if($event->getUser() !== $user) {
            $this->addFlash('danger','Accès interdit');
            return $this->redirectToRoute('event_list');
        }

        return $this->render("user/event_show.html.twig",['event' => $event]);
    }
}