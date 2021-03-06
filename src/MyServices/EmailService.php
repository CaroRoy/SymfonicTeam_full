<?php 

namespace App\MyServices;

use App\Entity\User;
use App\Entity\Event;
use Twig\Environment;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService {
    protected $mailer;
    protected $twig;

    public function __construct(MailerInterface $mailer, Environment $twig) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Envoie l'e-mail de validation d'une inscription
     */
    public function validateRegistration(User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Ton inscription sur Symfonic Team')
            ->htmlTemplate('email/registration.html.twig')
            ->context(['user' => $user, 'token' => $user->getAuthenticationToken()]);

        $this->mailer->send($email);
    }
    
    /**
     * Envoie l'e-mail pour la réinitialisation du mot de passe
     */
    public function sendResetPassword(User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Réinitialisation du mot de passe')
            ->htmlTemplate('email/reset_password.html.twig')
            ->context(['user' => $user, 'token' => $user->getAuthenticationToken()]);

        $this->mailer->send($email);
    }
    
    /**
     * Envoie l'e-mail d'information à un utilisateur nommé admin
     */
    public function sendNotificationRoleAdminSet(User $user, User $admin) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Tu es administrateur')
            ->htmlTemplate('email/role_set_admin.html.twig')
            ->context(['user' => $user, 'admin' => $admin]);

        $this->mailer->send($email);
    }

    /**
     * Envoie l'e-mail d'information à un utilisateur ayant perdu son rôle admin
     */
    public function sendNotificationRoleAdminDeleted(User $user, User $admin) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Tu n\'es plus administrateur')
            ->htmlTemplate('email/role_delete_admin.html.twig')
            ->context(['user' => $user, 'admin' => $admin]);

        $this->mailer->send($email);
    }
    
    /**
     * Envoie l'e-mail d'information qu'un nouveau participant s'est inscrit
     */
    public function sendNotificationNewParticipant(Event $event, User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Un nouveau participant à ta séance "' . $event->getTitle() . '"')
            ->htmlTemplate('email/event_new_participant.html.twig')
            ->context(['user' => $user, 'event' => $event]);

        $this->mailer->send($email);
    }

    /**
     * Envoie l'e-mail d'information qu'un admin a supprimé la séance proposée par l'utilisateur
     */
    public function sendNotificationAdminEventDeleted(Event $event, User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.caroline-roy.fr')
            ->to($user->getEmail())
            ->subject('Ta séance "' . $event->getTitle() . '" a été supprimée')
            ->htmlTemplate('email/admin_event_deleted.html.twig')
            ->context(['user' => $user, 'event' => $event]);

        $this->mailer->send($email);
    }

    /**
     * Envoie l'e-mail d'information aux participants que la séance a été supprimée
     */
    public function sendNotificationEventDeleted(Event $event, array $participants) {
        foreach ($participants as $p) {
            $email = (new TemplatedEmail())
                ->from('info@symfonic-team.caroline-roy.fr')
                ->to($p->getEmail())
                ->subject('La séance "' . $event->getTitle() . '" est annulée')
                ->htmlTemplate('email/event_deleted.html.twig')
                ->context(['user' => $p, 'event' => $event]);

            $this->mailer->send($email);

        }
    }      
}