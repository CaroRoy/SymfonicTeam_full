<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\MesServices\EmailService;
use App\MesServices\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageService $imageService, EmailService $emailService, EntityManagerInterface $em): Response
    {
        $user = new User();
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

            // Vérification âge :
            $formBirthdate = $form->get('birthdate')->getData();
            $birthdate = $formBirthdate->format('Y-m-d H:i:s');
            $today = date("Y-m-d H:i:s");
            $diff = date_diff(date_create($birthdate), date_create($today));
            $age = $diff->format('%y');

            if ($age < 18) {
                $this->addFlash('danger','Désolé, tu n\'as pas encore 18 ans. Mais nous serons ravis de t\'accueillir sur Symfonic Team dans quelque temps !');
                return $this->redirectToRoute('home');    
            }

            // récupération et enregistrement de l'avatar :
            $image = $form->get('avatar')->getData();
            $imageService->save($image,$user);

            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email

            $emailService->sendWelcome($user);
            $this->addFlash('success','Ton compte a bien été créé, tu peux maintenant te connecter');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
