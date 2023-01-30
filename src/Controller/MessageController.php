<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\RecruitmentProcess;
use App\Entity\User;
use App\Form\AdminSearchType;
use App\Form\ConversationType;
use App\Repository\MessageRepository;
use App\Repository\NotifRepository;
use App\Repository\RecruitmentProcessRepository;
use App\Service\NewNotif;
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

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);
        $messageQuery = $messageRepository->getInbox($user);
        $receivedMessages = $paginator->paginate(
            $messageQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->renderForm('message/conversationlist.html.twig', [
            'controller_name' => 'MessageController',
            'receivedMessages' => $receivedMessages,
            'form' => $form,
        ]);
    }

    #[Route('/recruitmentProcess/{recruitmentProcess<\d+>}', name: 'conversation')]
    #[Entity('recruitmentProcess', options: ['mapping' => ['recruitmentProcess' => 'id']])]
    public function conversation(
        RecruitmentProcess $recruitmentProcess,
        MessageRepository $messageRepository,
        Request $request,
        PaginatorInterface $paginator,
        RecruitmentProcessRepository $processRepo,
        NewNotif $newNotif,
        NotifRepository $notifRepository,
    ): Response {

        $messages = $messageRepository->findBy(['recruitmentProcess' => $recruitmentProcess], ['date' => 'ASC']);
        $message = new Message();
        $form = $this->createForm(ConversationType::class, $message);
        $form->handleRequest($request);

        /**
         * @var ?User $user
         */
        $user = $this->getUser();

        $newNotif->verifyIfIsRead($recruitmentProcess);

            $userConsultant = $recruitmentProcess->getExternaticConsultant()->getUser();

        if (($user !== $userConsultant) && ($user !== $recruitmentProcess->getCandidat()->getUser())) {
            $this->addFlash('danger', 'Vous ne pouvez pas accéder à cette conversation');
            return $this->redirectToRoute('message_index');
        }

        if ($this->isGranted('ROLE_CANDIDAT')) {
            $recruitmentProcess->setReadByCandidat(true);
        } else {
            $recruitmentProcess->setReadByConsultant(true);
        }
        $processRepo->save($recruitmentProcess, true);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setRecruitmentProcess($recruitmentProcess);
            $message->setSendBy($user);
            if ($this->isGranted('ROLE_CANDIDAT')) {
                $message->setSendTo($userConsultant);
            } else {
                $message->setSendTo($recruitmentProcess->getCandidat()->getUser());
            }
            $messageRepository->save($message, true);
            $newNotif->newMessageNotif($message, $recruitmentProcess);

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->render('_include/_newMessageTurbo.html.twig', ['message' => $message]);
            }

            // If the client doesn't support JavaScript, or isn't using Turbo, the form still works as usual.
            // Symfony UX Turbo is all about progressively enhancing your apps!
            return $this->redirectToRoute('message_conversation', [], Response::HTTP_SEE_OTHER);
        }


        $otherConvQuery = $messageRepository->getInbox($user);
        $otherConversations = $paginator->paginate(
            $otherConvQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->renderForm('message/showconversation.html.twig', [
            'recruitmentProcess' => $recruitmentProcess,
            'convMessages' => $messages,
            'form' => $form,
            'receivedMessages' => $otherConversations
        ]);
    }
}
