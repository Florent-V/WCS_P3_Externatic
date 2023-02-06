<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class NewEmail
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer, private readonly ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
    }
    public function sendMail(string $sendTo, string $subject, string $mailLocation, array $renderViewArray = []): void
    {
        $email = (new TemplatedEmail())
            ->to($sendTo)
            ->from($this->parameterBag->get('mailer_from'))
            ->subject($subject)
            ->htmlTemplate($mailLocation)
        ->context($renderViewArray);
        $this->mailer->send($email);
    }
}
