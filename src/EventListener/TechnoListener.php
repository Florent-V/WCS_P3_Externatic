<?php

namespace App\EventListener;

use App\Entity\Techno;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Techno::class)]
class TechnoListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function postPersist(Techno $techno, LifecycleEventArgs $event): void
    {
        if (!$techno->isFixture()) {
            foreach ($this->userRepository->findByRole("ADMIN") as $admin) {
                $email = (new TemplatedEmail())
                    ->to($admin->getEmail())
                    ->from($this->parameterBag->get('mailer_from'))
                    ->subject('Nouvelle Techno')
                    ->htmlTemplate('annonce/newTechnoEmail.html.twig')
                    ->context(['techno' => $techno]);
                $this->mailer->send($email);
            }
        }
    }
}
