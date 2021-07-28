<?php

namespace App\Controller\Event;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventListController extends AbstractController {
    /**
     * @Route("/seances", name="event_list")
     */
    public function list(EventRepository $eventRepository, PaginatorInterface $paginatorInterface, Request $request): Response {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger','merci de te connecter pour accéder aux séances publiées');
            return $this->redirectToRoute('home');
        }
        
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $events = $eventRepository->findSearch($data);
        $all = $events->getTotalItemCount();
        // on part à la page 1 par défaut, sinon le numéro de la page appelée, et on présente 5 events par page
        // $events = $paginatorInterface->paginate($all, $request->query->getInt('page', 1), 5);

        return $this->render("event/event_list.html.twig",[
            'all' => $all,
            'events' => $events,
            'form' => $form->createView()
        ]);
    }
}