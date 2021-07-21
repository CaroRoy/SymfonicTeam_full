<?php 

namespace App\MesServices;

use App\Entity\User;
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
}