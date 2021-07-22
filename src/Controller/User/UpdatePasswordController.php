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
        $form = $this->createForm(UpdatePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $passwordEncoder->encodePassword($user,$form->get('oldPassword')->getData());

            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
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
