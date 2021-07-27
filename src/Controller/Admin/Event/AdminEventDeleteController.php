<?php 

namespace App\Controller\Admin\Event;

use App\Entity\ReplyEventUser;
use App\MyServices\EmailService;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventDeleteController extends AbstractController {
    /**
     * @Route("/admin/supprimer/seance-{id}", name="admin_event_delete")
     */
    public function delete(int $id, EventRepository $eventRepository,ReplyEventUserRepository $replyEventUserRepository ,EntityManagerInterface $em, EmailService $emailService) : RedirectResponse {
        $event = $eventRepository->find($id);
        $user = $event->getUser();
        $replys = $replyEventUserRepository->findBy(['event' => $event, 'replyType' => ReplyEventUser::OK]);

        // On récupère tous les participants inscrits
        $participants = [];
        foreach ($replys as $r) {
            $participants[] = $r->getUser();
        }

        if (!$event) {
            $this->addFlash('danger','Cette séance n\'existe pas');
            return $this->redirectToRoute('admin_event_list');
        }
        
        $em->remove($event);
        $em->flush();

        $emailService->sendNotificationEventDeleted($event, $participants);
        $emailService->sendNotificationAdminEventDeleted($event, $user);
        $this->addFlash('success','La séance a bien été supprimée');
        return $this->redirectToRoute('admin_event_list');
    }
}