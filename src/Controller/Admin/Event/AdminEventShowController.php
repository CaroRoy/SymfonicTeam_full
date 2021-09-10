<?php 

namespace App\Controller\Admin\Event;

use App\Entity\ReplyEventUser;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\ReplyEventUserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventShowController extends AbstractController {
    /**
     * Affiche le détail d'une séance
     * 
     * @Route("admin/details/seance-{id}", name="admin_event_show")
     */
    public function show(int $id, EventRepository $eventRepository, ReplyEventUserRepository $replyEventUserRepository, CommentRepository $commentRepository, PaginatorInterface $paginatorInterface, Request $request) : Response {
        $event = $eventRepository->find($id);

        if (!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('admin_event_list');
        }

        $replys = $replyEventUserRepository->findBy(['event' => $event, 'replyType' => ReplyEventUser::OK]);
        $allComments = $commentRepository->findBy(['event' => $event], ['createdAt' => 'DESC']);
        // on part à la page 1 par défaut, sinon le numéro de la page appelée, et on présente 5 events par page
        $comments = $paginatorInterface->paginate($allComments, $request->query->getInt('page', 1), 5);

        return $this->render("admin/event_show.html.twig",[
            'event' => $event,
            'replys' => $replys,
            'allComments' => $allComments,
            'comments' => $comments
        ]);
    }
}