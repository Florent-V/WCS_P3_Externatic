<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Security("is_granted('ROLE_CANDIDAT') or is_granted('ROLE_CONSULTANT')")]
#[Route('/message', name: 'message_')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        MessageRepository $messageRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();

//        $receivedMessages = $messageRepository->findBy(['sendTo' => $user], ["date" => "DESC"]);
        $receivedMessages = $messageRepository->getInbox("sendTo", $user->getId());
        $receivedMessages = $paginator->paginate(
            $receivedMessages,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('message/conversationlist.html.twig', [
            'controller_name' => 'MessageController',
            'receivedMessages' => $receivedMessages,
        ]);
    }

    #[Route('/{recruitmentProcess<\d+>}/{message<\d+>}', name: 'index')]
    #[Entity('recruitmentProcess', options: ['mapping' => ['recruitmentProcess' => 'id']])]
    #[Entity('Message', options: ['mapping' => ['message' => 'id']])]
    public function conversation(
        RecruitmentProcessRepository $processRepository,
        MessageRepository $messageRepository
    ): response {
        return $this->render('message/showconversation.html.twig', [
        ]);
    }
}
