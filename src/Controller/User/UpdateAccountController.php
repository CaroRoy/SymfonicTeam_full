<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\MesServices\EmailService;
use App\MesServices\ImageService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdateAccountController extends AbstractController {
    /**
     * @Route("/mon-compte", name="update_account")
     */
    public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageService $imageService, EmailService $emailService, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            /** @var User $user */
            $user = $form->getData();
            $image = $form->get('avatar')->getData();
            $imageService->edit($image,$user, $user->getAvatar());

            $em->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success','Ton compte a bien été modifié');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update_account.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
