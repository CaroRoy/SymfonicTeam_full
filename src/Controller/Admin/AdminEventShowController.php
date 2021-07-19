<?php 

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventShowController extends AbstractController {
    /**
     * @Route("admin/details/seance-{id}", name="admin_event_show")
     */
    public function show(int $id, EventRepository $eventRepository) : Response {
        $event = $eventRepository->find($id);

        if (!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render("admin/event_show.html.twig",['event' => $event]);
    }
}