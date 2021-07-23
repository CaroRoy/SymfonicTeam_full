<?php 

namespace App\Controller\User\Reply;

use App\Form\CommentType;
use App\Entity\ReplyEventUser;
use App\Repository\CommentRepository;
use App\Repository\ReplyEventUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserParticipateShowController extends AbstractController {
    /**
     * @Route("mes-participations/details/seance-{id}", name="user_participate_show")
     */
    public function show(int $id, ReplyEventUserRepository $replyEventUserRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $em, PaginatorInterface $paginatorInterface) : Response {
        $user = $this->getUser();
        $reply = $replyEventUserRepository->find($id);

        if (!$reply) {
            $this->addFlash('warning','Cette séance n\'existe pas');
            return $this->redirectToRoute('user_reply_list');
        }

        if($reply->getUser() !== $user) {
            $this->addFlash('danger','Erreur : cette action est impossible');
            return $this->redirectToRoute('user_reply_list');
        }

        $event = $reply->getEvent();
        $participants = $replyEventUserRepository->findBy(['event' => $event, 'replyType' => ReplyEventUser::OK]);
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

            return $this->redirectToRoute('user_participate_show',['id' => $id]);
        }
                
        return $this->render("user/participate_event_show.html.twig",[
            'reply' => $reply,
            'event' => $event,
            'participants' => $participants,
            'allComments' => $allComments,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }
}