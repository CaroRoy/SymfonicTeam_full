<?php

namespace App\Controller\User;

use App\Entity\User;
use App\MyServices\ImageService;
use App\Form\UpdateAccountFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateAccountController extends AbstractController {
    /**
     * @Route("/mon-compte", name="update_account")
     */
    public function update(Request $request, ImageService $imageService, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UpdateAccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $image = $form->get('avatar')->getData();
            $imageService->edit($image ,$user, $user->getAvatar());

            $em->flush();

            $this->addFlash('success','Ton compte a bien été modifié');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update_account.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
