<?php

namespace App\Command;

use App\Repository\NotifRepository;
use App\Repository\UserRepository;
use App\Service\NewEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:notifSummary',
    description: 'Sent a summary of all notif',
)]
class NotifSummaryCommand extends Command
{
    private UserRepository $userRepository;
    private NewEmail $newEmail;
    private ?NotifRepository $notifRepository = null;
    public function __construct(UserRepository $userRepository, NewEmail $newEmail, NotifRepository $notifRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->newEmail = $newEmail;
        $this->notifRepository = $notifRepository;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->notifRepository->findBy(['inSummary' => true]) as $notif) {
            if (!$notif->isActive()) {
                $notif->setActive(true);
            } else {
                $notif->setInSummary(false);
                $notif->setActive(false);
            }
            $this->notifRepository->save($notif, true);
        }
        foreach ($this->userRepository->findWithSummary() as $user) {
            $this->newEmail->sendMail(
                $user->getEmail(),
                'Resumé des dernières nouveauté',
                'notif/summaryEmail.html.twig',
            );
        }

        return Command::SUCCESS;
    }
}
