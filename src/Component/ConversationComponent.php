<?php

namespace App\Component;

use App\Entity\RecruitmentProcess;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('conversation')]
final class ConversationComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?RecruitmentProcess $recruitmentProcess = null;

    public function __construct(private readonly MessageRepository $messageRepository)
    {
    }

    public function getConvMessages(): array
    {
        return $this->messageRepository->findBy(['recruitmentProcess' => $this->recruitmentProcess], ['date' => 'ASC']);
    }
}
