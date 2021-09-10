<?php 

namespace App\Controller\User\Event;

use App\Form\CommentType;
use App\Entity\ReplyEventUser;
use App\Repository\EventRepository;
use App\Repository\CommentRepository;
use App\Repository\ReplyEventUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventShowController extends AbstractController {
    /**
     * Affiche le détail d'une séance proposée par l'utilisateur
     * 
     * @Route("/details/seance-{id}", name="user_event_show")
     */
    public function show(int $id, EventRepository $eventRepository, ReplyEventUserRepository $replyEventUserRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $em, PaginatorInterface $paginatorInterface) : Response {
        $user = $this->getUser();
        $event = $eventRepository->find($id);

        if (!$event) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_event_list');
        }

        if($event->getUser() !== $user) {
            $this->addFlash('danger','Accès interdit');
            return $this->redirectToRoute('event_list');
        }

        $replys = $replyEventUserRepository->findBy(['event' => $event, 'replyType' => ReplyEventUser::OK]);
        $allComments = $commentRepository->findBy(['event' => $event], ['createdAt' => 'DESC']);
        // on part à la page 1 par défaut, sinon le numéro de la page appelée, et on présente 5 events par page
        $comments = $paginatorInterface->paginate($allComments, $request->query->getInt('page', 1), 5);

        // Formulaire pour créer un nouveau message :
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setEvent($event);
            $comment->setUser($user);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success','Ton commentaire a bien été ajouté');

            return $this->redirectToRoute('user_event_show',['id' => $id]);
        }


        return $this->render("user/event_show.html.twig",[
            'event' => $event,
            'replys' => $replys,
            'allComments' => $allComments,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }
}