<?php

namespace App\Controller;

use App\Repository\NotifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotifController extends AbstractController
{
    #[Route('/notif', name: 'app_notif')]
    public function index(NotifRepository $notifRepository): Response
    {
        $notification = $notifRepository->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('notif/index.html.twig', [
            'notification' => $notification,
        ]);
    }
}
