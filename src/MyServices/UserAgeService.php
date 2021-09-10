<?php

namespace App\MyServices;

class UserAgeService {

    /**
     * Calcule un âge
     * 
     * @param object $form Le formulaire qui contient le champ "date de naissance"
     * @return int
     */
    public function getAge(object $form) : int {
        // On récupère la date de naissance dans le formulaire :
        $formBirthdate = $form->get('birthdate')->getData();
        // On la transorme en format date
        $birthdate = $formBirthdate->format('Y-m-d H:i:s');
        // On récupère la date actuelle au même format
        $today = date("Y-m-d H:i:s");
        // On calcule la différence
        $difference = date_diff(date_create($birthdate), date_create($today));
        // On retourne l'âge, qui est la différence en nombre d'années:
        $age = $difference->format('%y');
        return $age;
    }
}