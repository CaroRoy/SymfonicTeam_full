<?php 

namespace App\MesServices;

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
    
    public function sendWelcome(User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.fr')
            ->to($user->getEmail())
            ->subject('Bienvenue sur Symfonic Team')
            ->htmlTemplate('email/welcome.html.twig')
            ->context(['user' => $user]);

        $this->mailer->send($email);
    }
    
    public function sendNotificationRoleAdminSet(User $user, User $admin) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.fr')
            ->to($user->getEmail())
            ->subject('Tu es administrateur')
            ->htmlTemplate('email/role_set_admin.html.twig')
            ->context(['user' => $user, 'admin' => $admin]);

        $this->mailer->send($email);
    }

    public function sendNotificationRoleAdminDeleted(User $user, User $admin) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.fr')
            ->to($user->getEmail())
            ->subject('Tu n\'es plus administrateur')
            ->htmlTemplate('email/role_delete_admin.html.twig')
            ->context(['user' => $user, 'admin' => $admin]);

        $this->mailer->send($email);
    }
    
    public function sendNotificationNewParticipant(Event $event, User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.fr')
            ->to($user->getEmail())
            ->subject('Un nouveau participant à ta séance "' . $event->getTitle() . '"')
            ->htmlTemplate('email/event_new_participant.html.twig')
            ->context(['user' => $user, 'event' => $event]);

        $this->mailer->send($email);
    }

    public function sendNotificationAdminEventDeleted(Event $event, User $user) {
        $email = (new TemplatedEmail())
            ->from('info@symfonic-team.fr')
            ->to($user->getEmail())
            ->subject('Ta séance "' . $event->getTitle() . '" a été supprimée')
            ->htmlTemplate('email/admin_event_deleted.html.twig')
            ->context(['user' => $user, 'event' => $event]);

        $this->mailer->send($email);
    }


    public function sendNotificationEventDeleted(Event $event, array $participants) {
        foreach ($participants as $p) {
            $email = (new TemplatedEmail())
                ->from('info@symfonic-team.fr')
                ->to($p->getEmail())
                ->subject('La séance "' . $event->getTitle() . '" est annulée')
                ->htmlTemplate('email/event_deleted.html.twig')
                ->context(['user' => $p, 'event' => $event]);

            $this->mailer->send($email);

        }
    }      
}