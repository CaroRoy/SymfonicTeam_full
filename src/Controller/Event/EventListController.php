<?php

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventListController extends AbstractController {
    /**
     * @Route("/seances", name="event_list")
     */
    public function list(EventRepository $eventRepository, PaginatorInterface $paginatorInterface, Request $request): Response {
        $all = $eventRepository->findAll();
        // on part à la page 1 par défaut, sinon le numéro de la page appelée, et on présente 5 events par page
        $events = $paginatorInterface->paginate($all, $request->query->getInt('page', 1), 5);

        return $this->render("event/event_list.html.twig",['all' => $all, 'events' => $events]);
    }
}