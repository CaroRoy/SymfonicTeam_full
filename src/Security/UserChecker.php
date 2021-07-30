<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface {
    public function checkPreAuth(UserInterface $user)
    {
        // si $user n'est pas une instance de l'entité User, alors on ne fait rien
        if (!$user instanceof User) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // vérification du champ AuthenticationToken : s'il est différent de null, alors le compte n\'est pas actif et l'utilisateur ne peut pas se connecter
        if ($user->getAuthenticationToken() !== null) {
            throw new CustomUserMessageAccountStatusException('Ton compte n\'as pas été activé. Merci de cliquer sur le bouton de confirmation dans l\'e-mail que tu as reçu.');
        }
    }
}