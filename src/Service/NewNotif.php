<?php

namespace App\Service;

use App\Entity\Annonce;
use App\Entity\Message;
use App\Entity\Notif;
use App\Entity\RecruitmentProcess;
use App\Repository\CandidatRepository;
use App\Repository\NotifRepository;
use App\Repository\SearchProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class NewNotif extends AbstractController
{
    private NotifRepository $notifRepository;
    private CandidatRepository $candidatRepository;
    private SearchProfileRepository $profileRepository;
    public function __construct(
        NotifRepository $notifRepository,
        CandidatRepository $candidatRepository,
        SearchProfileRepository $profileRepository,
    ) {
        $this->notifRepository = $notifRepository;
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
                $searchProfile->getSearchQuery()['searchQuery'] == "" ||
                strpos($annonce->getTitle(), $searchProfile->getSearchQuery()['searchQuery'])
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
}
