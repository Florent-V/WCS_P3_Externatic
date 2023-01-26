<?php

namespace App\Controller;

use App\Entity\Appointement;
use App\Entity\RecruitmentProcess;
use App\Entity\Candidat;
use App\Entity\User;
use App\Form\AdminSearchType;
use App\Form\AppointmentType;
use App\Form\RecruitmentProcessNotesType;
use App\Repository\AnnonceRepository;
use App\Repository\AppointementRepository;
use App\Repository\CertificationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
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

    #[Route('/recruitments', name: 'synthesis', methods: ['GET'])]
    public function processSynthesis(
        Request $request,
        RecruitmentProcessRepository $recruitProcessRepo,
        PaginatorInterface $paginator
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $synthesisQuery = $recruitProcessRepo->searchInProcess($user->getConsultant(), 0, $data['search']);
        } else {
            $synthesisQuery = $recruitProcessRepo->getRecruitmentProcessConsultant();
        }



        $synthesis = $paginator->paginate(
            $synthesisQuery,
            $request->query->getInt('page', 1),
            8
        );

        return $this->renderForm('externatic_consultant/process-synthesis.html.twig', [
            'synthesis' => $synthesis,
            'form' => $form,
        ]);
    }

    #[Route('/recruitments/{id}', name: 'recruitment_process_show', methods: ['GET', 'POST'])]
    public function recruitmentProcessShow(
        Request $request,
        RecruitmentProcessRepository $recruitProcessRepo,
        RecruitmentProcess $recruitmentProcess,
        AppointementRepository $appointmentRepo,
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (
            $recruitProcessRepo->getRelationToRecruitmentProcess($recruitmentProcess) != "Consultant" &&
            !($this->isGranted('ROLE_ADMIN'))
        ) {
            $this->addFlash('danger', "Vous n'avez pas les droits pour visualiser à ce processus");
            return $this->redirectToRoute('consultant_board');
        }

        //Formulaire des notes
        $notesForm = $this->createForm(RecruitmentProcessNotesType::class, $recruitmentProcess);
        $notesForm->handleRequest($request);
        if ($notesForm->isSubmitted() && $notesForm->isValid()) {
            $recruitProcessRepo->save($recruitmentProcess, true);

            return $this->redirectToRoute(
                'consultant_recruitment_process_show',
                ['id' => $recruitmentProcess->getId()]
            );
        }

        //Formulaire des RDVs
        $appointement = new Appointement();
        $appointmentForm = $this->createForm(AppointmentType::class, $appointement);
        $appointmentForm->handleRequest($request);
        if ($appointmentForm->isSubmitted() && $appointmentForm->isValid()) {
            $appointement->setRecruitmentProcess($recruitmentProcess);
            $appointement->setConsultant($user->getConsultant());
            $appointmentRepo->save($appointement, true);
            return $this->redirectToRoute(
                'consultant_recruitment_process_show',
                ['id' => $recruitmentProcess->getId()]
            );
        }

        return $this->renderForm('externatic_consultant/recruitmentProcessShow.html.twig', [
            'recruitmentProcess' => $recruitmentProcess,
            'notesForm' => $notesForm,
            'appointmentForm' => $appointmentForm
        ]);
    }

    #[Route('/candidat/{id}', name: 'app_candidat_show', methods: ['GET'])]
    public function show(
        Candidat $candidat,
        ExperienceRepository $experienceRepository,
        CertificationRepository $certificationRepo
    ): Response {

        $user = $candidat->getUser();
        $curriculum = $candidat->getCurriculum();

        $hardSkills = $curriculum->getSkills()->getHardSkill();
        $softSkills = $curriculum->getSkills()->getSoftSkill();
        $languages = $curriculum->getSkills()->getLanguages();
        $hobbies = $curriculum->getHobbie();

        $experiences = $experienceRepository->findBy(
            ['curriculum' => $curriculum],
            ['beginning' => 'ASC'],
            10
        );

        $certifications = $certificationRepo->findBy(
            ['curriculum' => $curriculum],
            ['year' => 'ASC'],
            10
        );

        return $this->render('candidat/profile.html.twig', [
            'user' => $user,
            'experiences' => $experiences,
            'certifications' => $certifications,
            'hardSkills' => $hardSkills,
            'softSkills' => $softSkills,
            'languages' => $languages,
            'hobbies' => $hobbies
        ]);
    }
}
