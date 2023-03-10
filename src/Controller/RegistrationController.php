<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Curriculum;
use App\Entity\Skills;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly EmailVerifier $emailVerifier
    ) {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $authenticator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //Affectation du rôle
            $user->setRoles(['ROLE_CANDIDAT']);
            //Creation of Candidat Entity and set to User
            $candidat = new Candidat();
            $user->setCandidat($candidat);
            //Creation of Curriculum Entity and set to Candidat
            $curriculum = new Curriculum();
            $candidat->setCurriculum($curriculum);
            //Creation df Skills entity
            $skills = new Skills();
            $skills->setCurriculum($curriculum);
            //Persit & Flush
            $entityManager->persist($user);
            $entityManager->persist($candidat);
            $entityManager->persist($curriculum);
            $entityManager->persist($skills);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from('no-reply@externatic.fr')
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre compte a bien été créé ! Un mail vous a été envoyé
            pour valider votre compte et confirmer l\'adresse mail');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
            $user->setIsVerified(true);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse mail a bien été vérifiée, vous pouvez désormais vous connecter !');

        return $this->redirectToRoute('home');
    }

    /**
     * @throws ResetPasswordExceptionInterface
     */
    #[Route('/verify-consultant/{token}', name: 'app_consultant_verify')]
    public function verifyAccount(
        string $token,
        EntityManagerInterface $entityManager
    ): Response {
        /**
         * @var ?User $user
         */
        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        $user->setIsVerified(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_reset_password', [ 'token' => $token], Response::HTTP_SEE_OTHER);
    }
}
