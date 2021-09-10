<?php

namespace App\Controller\Admin\User;

use App\MyServices\EmailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSetRoleController extends AbstractController {
    /**
     * Attribut le rôle admin à un utilisateur
     * 
     * @Route("admin/details/utilisateur-{id}/ajout-role", name="admin_set_role")
     */
    public function setAdmin(int $id, UserRepository $userRepository, EntityManagerInterface $em, EmailService $emailService) : RedirectResponse {
        $user = $userRepository->find($id);

        if(!$user) {
            $this->addFlash('danger','Cet utilisateur n\'existe pas');
            return $this->redirectToRoute('admin_user_list');
        }

        // je m'assure que le rôle sera bien attribué en vidant d'abord le tableau Roles (un utilisateur ne peut avoir qu'un seul rôle):
        $user->setRoles([]);
        $user->setRoles(["ROLE_ADMIN"]);

        $em->flush();

        // je récupère l'admin pour indiquer son nom dans l'e-mail de notification :
        $admin = $this->getUser();
        $emailService->sendNotificationRoleAdminSet($user, $admin);

        $this->addFlash('success','L\'utilisateur ' . $user->getFirstName() .' ' . $user->getLastName() . ' a maintenant le rôle ADMIN');
        return $this->redirectToRoute('admin_user_show',['id' => $id]);
    }
}