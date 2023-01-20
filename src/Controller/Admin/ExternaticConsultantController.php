<?php

namespace App\Controller\Admin;

use App\Entity\ExternaticConsultant;
use App\Entity\User;
use App\Form\ConsultantType;
use App\Repository\ExternaticConsultantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/consultant')]
class ExternaticConsultantController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/index', name: 'app_consultant_index', methods: ['GET'])]
    public function index(
        ExternaticConsultantRepository $consultantRepository
    ): Response {

        return $this->render('admin/consultant/index.html.twig', [
            'consultants' => $consultantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_consultant_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(ConsultantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bytes = openssl_random_pseudo_bytes(10);
            $pass = bin2hex($bytes);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $pass
                )
            );
            //Affectation du rôle
            $user->setRoles(['ROLE_CONSULTANT']);
            //Creation of Skills entity
            $consultant = new ExternaticConsultant();
            $user->setConsultant($consultant);
            //Persist & Flush
            $entityManager->persist($user);
            $entityManager->persist($consultant);
            $entityManager->flush();

            // generate a signed url and email it to the user
            return $this->processSendingPasswordInitEmail(
                $form->get('email')->getData(),
                $mailer,
                $translator
            );
        }

        return $this->render('admin/consultant/new.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/check-email', name: 'app_consultant_check_email')]
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        $resetToken = $this->getTokenObjectFromSession();
        if (null === $resetToken) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('admin/consultant/confirmCreation.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    private function processSendingPasswordInitEmail(
        string $emailFormData,
        MailerInterface $mailer,
        TranslatorInterface $translator
    ): RedirectResponse {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('admin_app_consultant_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     '%s - %s',
            //     $translator
            //          ->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

            return $this->redirectToRoute('admin_app_consultant_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@externatic.fr', 'Externatic Security Bot'))
            ->to($user->getEmail())
            ->subject('Initialisation de votre mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.


        return $this->redirectToRoute('admin_app_consultant_check_email');
    }
}
