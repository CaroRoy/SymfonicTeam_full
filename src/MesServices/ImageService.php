<?php 

namespace App\MesServices;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ImageService {
    protected $containerBag;

    public function __construct(ContainerBagInterface $containerBag) {
        $this->containerBag = $containerBag;
    }

    public function save(object $image = null, object $entity) {
        if($image !== null) {
            $this->handleMoveImage($image, $entity);
        }
    }

    public function edit(object $image = null, object $entity,string $imageOriginal = null) {
        if($image !== null) {
            $this->handleMoveImage($image, $entity);

            //Processus de supression de l'image précédente
            $this->deleteImage($imageOriginal);
        }
    }

    public function deleteImage(string $imageUrl = null) {
        //Processus de supression de l'image précédente
        if($imageUrl !== null && $imageUrl !== User::IMAGE_DEFAUT_PATH) {
            $fileImageOriginal = $this->containerBag->get('app_images_directory') . '/..' . $imageUrl;

            if(file_exists($fileImageOriginal)) {
                unlink($fileImageOriginal);
            }
        }
    }

    public function handleMoveImage(object $image,object $entity) { 
        $file = md5(uniqid()) . '.' . $image->guessExtension();

        $image->move(
            $this->containerBag->get('app_images_directory'),
            $file
        );

        $entity->setAvatar('/uploads/'. $file);
    }
}