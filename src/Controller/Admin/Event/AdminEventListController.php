<?php

namespace App\Controller\Admin\Event;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventListController extends AbstractController {
    /**
     * Affiche la liste des séances
     * 
     * @Route("admin/seances", name="admin_event_list")
     */
    public function list(EventRepository $eventRepository, Request $request): Response {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $events = $eventRepository->findSearch($data);
        $all = $events->getTotalItemCount();

        return $this->render("admin/event_list.html.twig",[
            'all' => $all,
            'events' => $events,
            'form' => $form->createView()
        ]);
    }
}