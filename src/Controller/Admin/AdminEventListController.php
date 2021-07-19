<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventListController extends AbstractController {
    /**
     * @Route("admin/seances", name="admin_event_list")
     */
    public function list(EventRepository $eventRepository): Response {
        $events = $eventRepository->findAll();

        return $this->render("admin/event_list.html.twig",['events' => $events]);
    }
}