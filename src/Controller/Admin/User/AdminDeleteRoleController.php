<?php

namespace App\Controller\Admin\User;

use App\MyServices\EmailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDeleteRoleController extends AbstractController {
    /**
     * Ote le rôle admin à un utilisateur
     * 
     * @Route("admin/details/utilisateur-{id}/suppression-role", name="admin_delete_role")
     */
    public function deleteRole(int $id, UserRepository $userRepository, EntityManagerInterface $em, EmailService $emailService) {
        $user = $userRepository->find($id);

        if(!$user) {
            $this->addFlash('danger','Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('admin_user_list');
        }

        // je vide le tableau Roles pour que l'utilisateur redevienne simple USER
        $user->setRoles([]);

        $em->flush();

        // je récupère l'admin pour indiquer son nom dans l'e-mail de notification :
        $admin = $this->getUser();
        $emailService->sendNotificationRoleAdminDeleted($user, $admin);
            
        $this->addFlash('success','L\'utilisateur ' . $user->getFirstName() .' ' . $user->getLastName() . ' n\'a plus le rôle ADMIN');
        return $this->redirectToRoute('admin_user_show',[ 'id' => $id]);
    }
}