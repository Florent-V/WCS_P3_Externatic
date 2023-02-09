<?php

namespace App\Service;

use App\Entity\Annonce;
use App\Entity\Appointement;
use App\Entity\Message;
use App\Entity\Notif;
use App\Entity\RecruitmentProcess;
use App\Repository\CandidatRepository;
use App\Repository\NotifRepository;
use App\Repository\SearchProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewNotif extends AbstractController
{
    private NotifRepository $notifRepository;
    private CandidatRepository $candidatRepository;
    private SearchProfileRepository $profileRepository;
    private MailerInterface $mailer;
    public function __construct(
        NotifRepository $notifRepository,
        CandidatRepository $candidatRepository,
        SearchProfileRepository $profileRepository,
        MailerInterface $mailer
    ) {
        $this->notifRepository = $notifRepository;
        $this->candidatRepository = $candidatRepository;
        $this->profileRepository = $profileRepository;
        $this->mailer = $mailer;
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
            $notification->setType('newAnnonce');
            $notification->setCreatedAt(new DateTime('now'));
            $notification->setUser($user);
            $notification->setAnnonce($annonce);
            $this->notifRepository->save($notification, true);
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
                ($searchProfile->getSearchQuery()['searchQuery'] == "" ||
                strpos($annonce->getTitle(), $searchProfile->getSearchQuery()['searchQuery'])) &&
                (empty($searchProfile->getContractType()) ||
                in_array($annonce->getContractType(), $searchProfile->getContractType()))
            ) {
                array_push($sentNotif, $searchProfile->getCandidat()->getUser()->getId());
                $notification = new Notif();
                $notification->setAnnonce($annonce);
                $notification->setType('newAnnonce');
                $notification->setCreatedAt(new DateTime('now'));
                $notification->setUser($searchProfile->getCandidat()->getUser());
                $this->notifRepository->save($notification, true);
            }
        }
    }
    public function newMessageNotif(Message $message, RecruitmentProcess $recruitmentProcess): void
    {
        $notification = new Notif();

        $notification->setMessage($message);
        $notification->setType('newMessage');
        $notification->setCreatedAt(new DateTime('now'));
        $notification->setUser($message->getSendTo());
        $notification->setWasRead(false);
        $this->notifRepository->save($notification, true);
    }

    public function verifyIfIsRead(RecruitmentProcess $recruitmentProcess): void
    {
        foreach (
            $this->notifRepository->findBy(
                ['wasRead' => false, 'user' => $this->getUser(), 'type' => 'newMessage']
            ) as $notif
        ) {
            if ($notif->getMessage()->getRecruitmentProcess()->getId() == $recruitmentProcess->getId()) {
                $notif->setWasRead(true);
                $this->notifRepository->save($notif, true);
            }
        }
    }

    public function newAppointmentNotif(Appointement $appointment): void
    {
        $notification = new Notif();

        $notification->setAppointment($appointment);
        $notification->setType('newAppointment');
        $notification->setCreatedAt(new DateTime('now'));
        $notification->setUser($appointment->getRecruitmentProcess()->getCandidat()->getUser());
        $this->notifRepository->save($notification, true);

        $email = (new Email())
            ->to($appointment->getRecruitmentProcess()->getCandidat()->getUser()->getEmail())
            ->from($this->getParameter('mailer_from'))
            ->subject('Nouveau rendez-vous')
            ->html($this->renderView('externatic_consultant/newMailAppointment.html.twig', [
                'appointment' => $appointment
            ]));
        $this->mailer->send($email);
        sleep(3);
    }
}
