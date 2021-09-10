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
     * Affiche la liste des séances
     * 
     * @Route("/seances", name="event_list")
     */
    public function list(EventRepository $eventRepository, Request $request): Response {       
        if (!$this->getUser()) {
            $this->addFlash('danger','Tu dois te connecter pour accéder aux séances publiées');
            return $this->redirectToRoute('app_login');
        }
        
        // Création du formulaire de filtre
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        // Récupération des résultats éventuellement filtrés selon certains critères
        $events = $eventRepository->findSearch($data);
        $all = $events->getTotalItemCount();

        return $this->render("event/event_list.html.twig",[
            'all' => $all,
            'events' => $events,
            'form' => $form->createView()
        ]);
    }
}