<?php

namespace App\Controller;

use App\Entity\Notif;
use App\Entity\User;
use App\Repository\NotifRepository;
use App\Repository\RecruitmentProcessRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[route('/notif', name: "notif_")]
class NotifController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function index(NotifRepository $notifRepository): Response
    {

        foreach ($notifRepository->findOlderThan15weeks() as $notif) {
            $notifRepository->remove($notif, true);
        }
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

    #[Route('/summary', name: 'summary')]
    public function summary(NotifRepository $notifRepository, RecruitmentProcessRepository $processRepository): Response
    {
        return $this->render('notif/summary.html.twig', [
            'notifAnnonce' => $notifRepository->findBy(['inSummary' => true, 'type' => 'newAnnonce']),
            'recruitmentProcesses' => $processRepository->countMessageSummary($this->getUser())
        ]);
    }
}
