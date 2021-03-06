<?php 

namespace App\Controller\User\Event;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventListController extends AbstractController {
    /**
     * Affiche la liste des séances proposées par l'utilisateur
     * 
     * @Route("/mes-seances", name="user_event_list")
     */
    public function list(EventRepository $eventRepository) : Response {
        $user = $this->getUser();

        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render("user/event_list.html.twig",['events' => $events]);
    }
}