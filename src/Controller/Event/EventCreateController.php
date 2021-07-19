<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventCreateController extends AbstractController {
    /**
     * @Route("nouvelle-seance", name="event_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository) {
        $email = $this->getUser()->getUsername();
        $user = $userRepository->findBy(['email' => $email]);

    
        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $event->setUser($user[0]);
            $em->persist($event);
            $em->flush();

            $this->addFlash('success','La séance a bien été publiée');

            return $this->redirectToRoute('event_list');
        }

        return $this->render('event/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}