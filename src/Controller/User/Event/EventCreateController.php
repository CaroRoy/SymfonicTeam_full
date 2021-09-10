<?php

namespace App\Controller\User\Event;

use App\Form\EventType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventCreateController extends AbstractController {
    /**
     * Crée une nouvelle séance
     * 
     * @Route("nouvelle-seance", name="event_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository) : Response {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger','Tu dois te connecter pour pouvoir proposer une séance');
            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $event->setUser($user);
            $em->persist($event);
            $em->flush();

            $this->addFlash('success','La séance a bien été publiée');

            return $this->redirectToRoute('user_event_list');
        }

        if ($form->isSubmitted() && !($form->isValid())) {
            $this->addFlash('warning', 'Erreur, merci de vérifier les champs du formulaire');
        }

        return $this->render('event/create.html.twig',['form' => $form->createView()]);
    }
}