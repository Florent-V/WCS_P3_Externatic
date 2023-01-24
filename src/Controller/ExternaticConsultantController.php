<?php

namespace App\Controller;

use App\Entity\RecruitmentProcess;
use App\Entity\User;
use App\Form\AdminSearchType;
use App\Repository\AnnonceRepository;
use App\Repository\AppointementRepository;
use App\Repository\CandidatRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_CONSULTANT')]
#[Route('/consultant', name: "consultant_")]
class ExternaticConsultantController extends AbstractController
{
    #[Route('/board', name: 'board')]
    public function board(AppointementRepository $appointRepository, MessageRepository $messageRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $weekAppointments = $appointRepository->findAppoitmentList($user->getConsultant()->getId(), "thisWeek");
        $otherAppointments = $appointRepository->findAppoitmentList($user->getConsultant()->getId(), "thisMonth");

        $messages = $messageRepository->findBy(["sendTo" => $user], ["date" => 'DESC'], 10);

        return $this->render('externatic_consultant/board.html.twig', [
            'controller_name' => 'ExternaticConsultantController',
            'weekAppointment' => $weekAppointments,
            'otherAppointment' => $otherAppointments,
            'messages' => $messages
        ]);
    }

    #[Route('/annonces', name: 'annonces')]
    public function viewAnnonces(
        AnnonceRepository $annonceRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $fetchedAnnonces = $annonceRepository->searchAnnonces($user->getConsultant(), 1, $data['search']);
        } else {
            $fetchedAnnonces = $annonceRepository->getConsultantAnnonces($user->getConsultant(), 1);
        }

        $annonces = $paginator->paginate(
            $fetchedAnnonces,
            $request->query->getInt('page', 1),
            8
        );

        return $this->renderForm('externatic_consultant/annonces.html.twig', [
            'annonces' => $annonces,
            'form' => $form
        ]);
    }

    #[Route('/annonces/archives', name: 'annonces_archives')]
    public function viewAnnoncesArchives(
        AnnonceRepository $annonceRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $fetchedAnnonces = $annonceRepository->searchAnnonces($user->getConsultant(), 0, $data['search']);
        } else {
            $fetchedAnnonces = $annonceRepository->getConsultantAnnonces($user->getConsultant(), 0);
        }

        $annonces = $paginator->paginate(
            $fetchedAnnonces,
            $request->query->getInt('page', 1),
            8
        );

        return $this->renderForm('externatic_consultant/annoncesArchives.html.twig', [
            'annonces' => $annonces,
            'form' => $form
        ]);
    }

    #[Route('/synthese', name:'synthesis', methods: ['GET'])]
    public function processSynthesis(
        Request $request,
        RecruitmentProcessRepository $recruitmentProcessRepo,
        PaginatorInterface $paginator
    ): Response {

        $synthesis = $recruitmentProcessRepo->findAll();

       // $paginate = $paginator->paginate(
         //   $synthesis,
           // $request->query->getInt('page', 1),
            //12

        return $this->render('externatic_consultant/process-synthesis.html.twig', [
           'synthesis' => $synthesis,

        ]);
    }
}
