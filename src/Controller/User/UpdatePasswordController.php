<?php

namespace App\Controller\User;

use App\Form\UpdatePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdatePasswordController extends AbstractController {
    /**
     * @Route("/changement-mot-de-passe", name="update_password")
     */
    public function resetPassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder) {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger','Tu dois te connecter pour pouvoir modifier ton mot de passe');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UpdatePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si l'ancien mot de passe est correct :
            if ($passwordEncoder->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                // encodage du nouveau password reçu via le form :
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
                // enregistrement dans le champ Password du User :
                $user->setPassword($newEncodedPassword);

                $em->flush();

                $this->addFlash('success', 'Ton nouveau mot de passe a été pris en compte');
                return $this->redirectToRoute('home');
                
            } else {
                $this->addFlash('danger', 'Ancien mot de passe incorrect');
                return $this->redirectToRoute('update_password');
            }
        }

        return $this->render('user/update_password.html.twig', ['form' => $form->createView()]);
    }
}
