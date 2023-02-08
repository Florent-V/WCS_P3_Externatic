<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier
    ) {
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isIsActive()) {
            throw new CustomUserMessageAccountStatusException(
                message: 'Vous avez demandé la suppression de vos données. L\'opération est en cours',
                code: 2000
            );
        }

        if (!$user->isVerified()) {
            // the message passed to this exception is meant to be displayed to the user
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@externatic.fr', 'Externatic Bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            throw new CustomUserMessageAccountStatusException(
                message: 'Votre compte <strong>n\'est pas activé</strong>, un mail vient de vous être envoyé pour
                        procéder à l\'activation ',
                code: 2000
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if (!$user->isVerified()) {
            throw new AccountExpiredException('...');
        }
    }
}
