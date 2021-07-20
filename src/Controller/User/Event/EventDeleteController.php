<?php 

namespace App\Controller\User\Event;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EventDeleteController extends AbstractController {
    /**
     * @Route("supprimer/seance-{id}", name="event_delete")
     */
    public function delete(int $id, EventRepository $eventRepository, EntityManagerInterface $em) : RedirectResponse {
        $event = $eventRepository->find($id);
        $user = $this->getUser();

        if (!$event) {
            $this->addFlash('danger','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        if($event->getUser() !== $user) {
            $this->addFlash('danger','Tu n\'es pas autorisé à supprimer cette séance');
            return $this->redirectToRoute('event_list');
        }

        $em->remove($event);
        $em->flush();

        $this->addFlash('success','La séance a bien été supprimée');
        return $this->redirectToRoute('user_event_list');
    }
}