<?php

namespace App\Controller\User;

use App\MyServices\EmailService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Form\ForgetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Tu es déjà connecté(e) !');
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/mot-de-passe-oublie", name="app_forgotten_password")
     */
    public function forgetPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $em, EmailService $emailService, TokenGeneratorInterface $tokenGenerator) {
        $form = $this->createForm(ForgetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // vérification que l'email correspond bien à un utilisateur en BDD :
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            if (!$user) {
                $this->addFlash('danger', 'Cette adresse e-mail ne correspond à aucun compte');
                return $this->redirectToRoute('app_login');
            }

            // génération d'un token :
            $token = $tokenGenerator->generateToken();

            // try/catch au cas où l'écriture en BDD ne fonctionne pas correctement :
            try {
                $user->setAuthenticationToken($token);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur est survenue : ' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // envoi d'un e-mail pour créer un nouveau mot de passe :
            $emailService->sendResetPassword($user);

            $this->addFlash('success', 'Un e-mail de réinitialisation de mot de passe vient de t\'être envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/forgotten_password.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/mot-de-passe/reinitialisation/{token}", name="app_reset_password")
     */
    public function resetPassword(string $token, UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em) {
        $user = $userRepository->findOneBy(['authenticationToken' =>$token]);

        if (!$user) {
            $this->addFlash('danger', 'Erreur : l\'identification a échoué');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // remise à null du token :
            $user->setAuthenticationToken(null);
            // encodage du nouveau mot de passe :
            $newEncodedPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            // enregistrement du nouveau mot de passe encodé dans le user :
            $user->setPassword($newEncodedPassword);

            $em->flush();

            $this->addFlash('success', 'Ton nouveau mot de passe a bien été pris en compte');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('security/create_new_password.html.twig', ['form' => $form->createView(), 'token' => $token]);
    }
}
