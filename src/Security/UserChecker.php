<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface {
    /**
     * Vérifie les informations d'un compte utilisateur avant validation du formulaire de connexion
     */
    public function checkPreAuth(UserInterface $user)
    {
        // si $user n'est pas une instance de l'entité User, alors on ne fait rien
        if (!$user instanceof User) {
            return;
        }
    }

    // dans PostAuth car on veut d'abord vérifier que les identifiants sont corrects, avant de vérifier si le compte est bien actif

    /**
     * Vérifie les informations d'un compte utilisateur après validation du formulaire de connexion
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // vérification du champ AuthenticationToken : s'il est différent de null, alors le compte n\'est pas actif et l'utilisateur ne peut pas se connecter
        if ($user->getAuthenticationToken() !== null) {
            throw new CustomUserMessageAccountStatusException('Merci de vérifier tes e-mails pour pouvoir te connecter');
        }
    }
}