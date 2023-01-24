<?php

namespace App\Service;

use App\Entity\Annonce;
use App\Entity\Message;
use App\Entity\Notif;
use App\Entity\RecruitmentProcess;
use App\Entity\SearchProfile;
use App\Entity\User;
use App\Repository\CandidatRepository;
use App\Repository\NotifRepository;
use App\Repository\SearchProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use DateTime;

class NewNotif extends AbstractController
{
    private NotifRepository $notifRepository;
    private MailerInterface $mailer;
    private CandidatRepository $candidatRepository;
    private SearchProfileRepository $profileRepository;
    public function __construct(
        NotifRepository $notifRepository,
        MailerInterface $mailer,
        CandidatRepository $candidatRepository,
        SearchProfileRepository $profileRepository
    ) {
        $this->notifRepository = $notifRepository;
        $this->mailer = $mailer;
        $this->candidatRepository = $candidatRepository;
        $this->profileRepository = $profileRepository;
    }

    public function notifNewAnnonce(Annonce $annonce): void
    {
        $sentNotif = [];
        $this->annonceByCriteria($annonce, $sentNotif);
        $this->annonceFavCompany($annonce, $sentNotif);
    }

    private function annonceFavCompany(Annonce $annonce, array &$sentNotif): void
    {
        $users = [];
        foreach ($this->candidatRepository->findByFavCompany($annonce->getCompany()) as $candidat) {
            array_push($users, $candidat->getUser());
        }

        foreach ($users as $user) {
            array_push($sentNotif, $user->getId());
            $notification = new Notif();
//            $notification->setContent($annonce->getTitle());
            $notification->setType('newAnnonce');
            $notification->setCreatedAt(new DateTime('now'));
            $notification->setUser($user);
            $notification->setParameter($annonce->getId());
            $notification->setWasRead(false);
            $this->notifRepository->save($notification, true);

            $email = (new Email())
                ->to($user->getEmail())
                ->from($this->getParameter('mailer_from'))
                ->subject('Une nouvelle Annonce de ' . $annonce->getCompany()->getName() . ' vient d\'étre créer')
                ->html($this->renderView('annonce/newEmail.html.twig', ['annonce' => $annonce]));

            $this->mailer->send($email);
            // sleep pour ne pas dépasser la limite de mailtrap, a enlever en prod
            sleep(3);
        }
    }

    private function annonceByCriteria(Annonce $annonce, array &$sentNotif): void
    {
        if ($annonce->getWorkTime()->h > 35) {
            $worktime = 1;
        } else {
            $worktime = 0;
        }
        foreach ($this->profileRepository->findBySearchProfile($annonce, $worktime) as $searchProfile) {
            if (
                $searchProfile->getSearchQuery()['searchQuery'] == "" ||
                strpos($annonce->getTitle(), $searchProfile->getSearchQuery()['searchQuery'])
            ) {
                array_push($sentNotif, $searchProfile->getCandidat()->getUser()->getId());
                $notification = new Notif();
//                $notification->setContent($annonce->getTitle());
                $notification->setType('newAnnonce');
                $notification->setCreatedAt(new DateTime('now'));
                $notification->setUser($searchProfile->getCandidat()->getUser());
                $notification->setParameter($annonce->getId());
                $notification->setWasRead(false);
                $this->notifRepository->save($notification, true);

                $email = (new Email())
                    ->to($searchProfile->getCandidat()->getUser()->getEmail())
                    ->from($this->getParameter('mailer_from'))
                    ->subject('Une nouvelle Annonce correspondant a vos critère vient d\'étre créer')
                    ->html($this->renderView('annonce/newEmail.html.twig', ['annonce' => $annonce]));

                $this->mailer->send($email);
                // sleep pour ne pas dépasser la limite de mailtrap, a enlever en prod
                sleep(3);
            }
        }
    }
    public function newMessageNotif(Message $message, RecruitmentProcess $recruitmentProcess): void
    {
        $notification = new Notif();
        if (!is_null($recruitmentProcess->getAnnonce())) {
            $notification->setContent([
                'title' => $recruitmentProcess->getAnnonce()->getTitle(),
                'firstName' => $message->getSendBy()->getFirstname(),
                'lastName' => $message->getSendBy()->getLastName(),
            ]);
        } else {
            $notification->setContent([
                'title' => $recruitmentProcess->getCompany()->getName(),
                'firstName' => $message->getSendBy()->getFirstname(),
                'lastName' => $message->getSendBy()->getLastName(),
            ]);
        }

        $notification->setType('newMessage');
        $notification->setCreatedAt(new DateTime('now'));
        $notification->setUser($message->getSendTo());
        $notification->setParameter($recruitmentProcess->getId());
        $notification->setWasRead(false);
        $this->notifRepository->save($notification, true);

        $email = (new Email())
            ->to($message->getSendTo()->getEmail())
            ->from($this->getParameter('mailer_from'))
            ->subject($message->getSendBy()->getFirstname() . $message->getSendBy()->getLastName() .
                'vous a envoyé un nouveau message')
            ->html($this->renderView(
                'annonce/newEmail.html.twig',
                [
                    'recruitementProcess' => $recruitmentProcess,
                    'message' => $message,
                ]
            ));

        $this->mailer->send($email);
        // sleep pour ne pas dépasser la limite de mailtrap, a enlever en prod
        sleep(3);
    }
}
