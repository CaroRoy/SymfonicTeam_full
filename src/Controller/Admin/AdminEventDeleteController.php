<?php 

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminEventDeleteController extends AbstractController {
    /**
     * @Route("/admin/supprimer/seance-{id}", name="admin_event_delete")
     */
    public function delete(int $id, EventRepository $eventRepository, EntityManagerInterface $em) : RedirectResponse {
        $event = $eventRepository->find($id);

        if (!$event) {
            $this->addFlash('danger','Cette séance n\'existe pas');
            return $this->redirectToRoute('admin_event_list');
        }
        
        if($this->getUser()->getRoles()[0] !== 'ROLE_ADMIN') {
            $this->addFlash('danger','Tu n\'es pas autorisé à supprimer cette séance');
            return $this->redirectToRoute('admin_event_list');
        }

        $em->remove($event);
        $em->flush();

        $this->addFlash('success','La séance a bien été supprimée');
        return $this->redirectToRoute('admin_event_list');
    }
}