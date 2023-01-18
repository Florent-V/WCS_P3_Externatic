<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\RecruitmentProcess;
use App\Entity\User;
use App\Form\ConversationType;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

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
        $messageQuery = $messageRepository->getInbox("sendTo", $user->getId());
        $receivedMessages = $paginator->paginate(
            $messageQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('message/conversationlist.html.twig', [
            'controller_name' => 'MessageController',
            'receivedMessages' => $receivedMessages,
        ]);
    }

    #[Route('/recruitmentProcess/{recruitmentProcess<\d+>}', name: 'conversation')]
    #[Entity('recruitmentProcess', options: ['mapping' => ['recruitmentProcess' => 'id']])]
    //#[Entity('Message', options: ['mapping' => ['message' => 'id']])]
    public function conversation(
        RecruitmentProcess $recruitmentProcess,
        MessageRepository $messageRepository,
        Request $request
    ): Response {
        $messages = $messageRepository->findBy(['recruitmentProcess' => $recruitmentProcess], ['date' => 'ASC']);
        $message = new Message();
        $form = $this->createForm(ConversationType::class, $message);
        $form->handleRequest($request);

        /**
         * @var ?User $user
         */
        $user = $this->getUser();

        if (!is_null($recruitmentProcess->getAnnonce())) {
            $consultant = $recruitmentProcess->getAnnonce()->getAuthor()->getUser();
        } else {
            $consultant = $recruitmentProcess->getCompany()->getExternaticConsultant()->getUser();
        }

        if (($user != $consultant) && ($user != $recruitmentProcess->getCandidat()->getUser())) {
            return $this->redirectToRoute('message_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setRecruitmentProcess($recruitmentProcess);
            $message->setSendBy($user);
            if ($this->isGranted('ROLE_CANDIDAT')) {
                $message->setSendTo($recruitmentProcess->getCompany()->getExternaticConsultant()->getUser());
            } else {
                $message->setSendTo($recruitmentProcess->getCandidat()->getUser());
            }
            $messageRepository->save($message, true);

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->render('_include/_newMessageTurbo.html.twig', ['message' => $message]);
            }

            // If the client doesn't support JavaScript, or isn't using Turbo, the form still works as usual.
            // Symfony UX Turbo is all about progressively enhancing your apps!
            return $this->redirectToRoute('message_conversation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/showconversation.html.twig', [
            'recruitmentProcess' => $recruitmentProcess,
            'messages' => $messages,
            'form' => $form
            ]);
    }
}
