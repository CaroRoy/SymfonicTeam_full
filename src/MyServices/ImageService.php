<?php 

namespace App\MyServices;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ImageService {
    protected $containerBag;

    public function __construct(ContainerBagInterface $containerBag) {
        $this->containerBag = $containerBag;
    }

    /**
     * Enregistre un avatar
     */
    public function save(object $image = null, object $entity) {
        if($image !== null) {
            $this->handleMoveImage($image, $entity);
        }
    }

    /**
     * Modifie un avatar
     */
    public function edit(object $image = null, object $entity,string $imageOriginal = null) {
        if($image !== null) {
            $this->handleMoveImage($image, $entity);

            //Processus de supression de l'image précédente
            $this->deleteImage($imageOriginal);
        }
    }

    /**
     * Supprime un avatar
     */
    public function deleteImage(string $imageUrl = null) {
        //Processus de supression de l'image précédente
        if($imageUrl !== null && $imageUrl !== User::IMAGE_DEFAUT_PATH) {
            $fileImageOriginal = $this->containerBag->get('app_images_directory') . '/..' . $imageUrl;

            if(file_exists($fileImageOriginal)) {
                unlink($fileImageOriginal);
            }
        }
    }

    /**
     * Gère la réception d'un avatar
     */
    public function handleMoveImage(object $image,object $entity) { 
        // création d'un nom pour le nouveau fichier, avec le nom de l'extension du fichier reçu :
        $file = md5(uniqid()) . '.' . $image->guessExtension();

        // enregistrement du fichier dans le dossier app_images_directory (soit 'uploads')
        $image->move(
            $this->containerBag->get('app_images_directory'),
            $file
        );

        // enregistrement du nom du fichier dans le champ Avatar de l'entité User :
        $entity->setAvatar('/uploads/'. $file);
    }
}