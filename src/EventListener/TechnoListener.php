<?php

namespace App\EventListener;

use App\Entity\Techno;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Techno::class)]
class TechnoListener extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function prePersist(Techno $techno, LifecycleEventArgs $event): void
    {
        foreach ($this->userRepository->findByRole("ADMIN") as $admin) {
            $email = (new Email())
                ->to($admin->getEmail())
                ->from($this->getParameter('mailer_from'))
                ->subject('Nouvelle Techno')
                ->html($this->renderView('annonce/newTechnoEmail.html.twig', ['techno' => $techno]));
            $this->mailer->send($email);
        }
    }
}
