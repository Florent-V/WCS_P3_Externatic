<?php

namespace App\Controller;

use App\Entity\Notif;
use App\Entity\User;
use App\Repository\NotifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[route('/notif', name: "notif_")]
class NotifController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function index(NotifRepository $notifRepository): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $notifications = $notifRepository->findBy(['user' => $user->getId()], ['createdAt' => 'DESC']);
        return $this->render('notif/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Notif $notif, NotifRepository $notifRepository): Response
    {
        $notifRepository->remove($notif, true);

        return $this->redirectToRoute('notif_show');
    }
}
