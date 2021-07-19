<?php

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventListController extends AbstractController {
    /**
     * @Route("/seances", name="event_list")
     */
    public function list(EventRepository $eventRepository): Response {
        $events = $eventRepository->findAll();

        return $this->render("event/event_list.html.twig",['events' => $events]);
    }
}