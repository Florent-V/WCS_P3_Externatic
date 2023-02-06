<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewEmail extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendMail(string $sendTo, string $subject, string $mailLocation, array $renderViewArray = []): void
    {
        $email = (new Email())
            ->to($sendTo)
            ->from($this->getParameter('mailer_from'))
            ->subject($subject)
            ->html($this->renderView($mailLocation, $renderViewArray));

        $this->mailer->send($email);
    }
}
