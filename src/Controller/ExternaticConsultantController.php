<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AppointementRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consultant', name:"consultant_")]
class ExternaticConsultantController extends AbstractController
{
    #[Route('/board', name: 'board')]
    public function board(AppointementRepository $appointRepository, MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $consultantId = ($user instanceof User) ? $user->getConsultant()->getId() : null;

        $weekAppointments = $appointRepository->findAppoitmentList($consultantId, "thisWeek");
        $otherAppointments = $appointRepository->findAppoitmentList($consultantId, "thisMonth");

        $messages = $messageRepository->findBy(["sendTo" => $user ], ["date" => 'DESC'], 10);

        return $this->render('externatic_consultant/board.html.twig', [
            'controller_name' => 'ExternaticConsultantController',
            'weekAppointment' => $weekAppointments,
            'otherAppointment' => $otherAppointments,
            'messages' => $messages
        ]);
    }
}
