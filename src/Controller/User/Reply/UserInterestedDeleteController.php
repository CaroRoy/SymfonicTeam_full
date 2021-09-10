<?php 

namespace App\Controller\User\Reply;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReplyEventUserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserInterestedDeleteController extends AbstractController {
    /**
     * Gère la suppression d'une séance par laquelle l'utilisateur n'est plus intéressé
     * 
     * @Route("/mes-favoris/supprimer/seance-{id}", name="user_interested_delete")
     */
    public function delete(int $id, ReplyEventUserRepository $replyEventUserRepository, EntityManagerInterface $em) : RedirectResponse {
        $reply = $replyEventUserRepository->find($id);
        $user = $this->getUser();

        if (!$reply) {
            $this->addFlash('danger','Tu n\'as pas enregistré cette séance');
            return $this->redirectToRoute('user_interested_list');
        }

        if($reply->getUser() !== $user) {
            $this->addFlash('danger','Erreur : cette action est impossible');
            return $this->redirectToRoute('user_interested_list');
        }

        $em->remove($reply);
        $em->flush();

        $this->addFlash('success','Cette séance a été supprimée de ta liste d\'intérêt');
        return $this->redirectToRoute('user_interested_list');
    }
}