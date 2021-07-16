<?php

namespace App\Controller\Event;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventCreateController extends AbstractController {
    /**
     * @Route("nouvelle-seance", name="event_create")
     */
    public function create(Request $request, EntityManagerInterface $em) {
        // $user = $this->security->getUser();

        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $em->persist($event);
            $em->flush();

            $this->addFlash('success','La séance a bien été publiée');

            return $this->redirectToRoute('event_list');
        }

        return $this->render('create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}