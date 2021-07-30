<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\MyServices\EmailService;
use App\MyServices\ImageService;
use App\MyServices\UserAgeService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageService $imageService, EmailService $emailService, UserAgeService $userAgeService, EntityManagerInterface $em, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Tu es déjà connecté(e) !');
            return $this->redirectToRoute('home');
        }

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

            // vérification âge :
            $age = $userAgeService->getAge($form);
            if ($age < 18) {
                $this->addFlash('danger','Désolé, tu n\'as pas encore 18 ans. Mais nous serons ravis de t\'accueillir sur Symfonic Team dans quelque temps !');
                return $this->redirectToRoute('home');    
            }

            // récupération et enregistrement de l'avatar :
            $image = $form->get('avatar')->getData();
            $imageService->save($image,$user);

            // génération du token pour l'activation du compte :
            $user->setAuthenticationToken($tokenGenerator->generateToken());

            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email

            $emailService->validateRegistration($user);
            $this->addFlash('success','Merci pour ton inscription ! Un e-mail de validation vient de t\'être envoyé');
            return $this->redirectToRoute('app_login');
        }

        if ($form->isSubmitted() && !($form->isValid())) {
            $this->addFlash('warning', 'Erreur, merci de vérifier les champs du formulaire');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("activation/{token}", name ="activation")
     */
    public function activation(string $token, UserRepository $userRepository, EntityManagerInterface $em) {
        $user = $userRepository->findOneBy(['authenticationToken' => $token]);

        // si pas d'utilisateur avec le token, on envoie une erreur 404 :
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur inconnu');
        }

        // suppression du token :
        $user->setAuthenticationToken(null);
        $em->flush();

        $this->addFlash('success', 'Compte activé ! Tu peux maintenant te connecter');
        return $this->redirectToRoute('app_login');
        
    }
}
