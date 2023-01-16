<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MessageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message', name: 'message_')]
class MessageController extends AbstractController
{
    #[Security("is_granted('ROLE_CANDIDAT') or is_granted('ROLE_CONSULTANT')")]
    #[Route('/', name: 'index')]
    public function index(MessageRepository $messageRepository): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();

        $sendMessages = $messageRepository->findBy(['sendBy' => $user]);

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'sendMessages' => $sendMessages,
        ]);
    }
}
