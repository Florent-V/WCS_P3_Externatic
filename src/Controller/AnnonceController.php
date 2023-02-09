<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Company;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\RecruitmentProcess;
use App\Form\AnnonceType;
use App\Form\MessageType;
use App\Repository\AnnonceRepository;
use App\Repository\NotifRepository;
use App\Repository\UserRepository;
use App\Repository\CandidatRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
use App\Service\NewNotif;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

#[route('/annonce', name: "annonce_")]
class AnnonceController extends AbstractController
{
    #[Route('/search/results', name: 'search_results')]
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
        AnnonceRepository $annonceRepository,
        PaginatorInterface $paginator
    ): Response {
        $queryAnnonces = $annonceRepository->annonceFinder($request->get('form'));

        $searchData = $request->get('form');

        if (isset($searchData["company"])) {
            unset($searchData["company"]);
        }
        if (isset($searchData["techno"])) {
            unset($searchData["techno"]);
        }
        if (isset($searchData["salaryMin"])) {
            $searchData["salaryMin"] = intVal($searchData["salaryMin"]);
        }

        $annonces = $paginator->paginate(
            $queryAnnonces,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('annonce/results.html.twig', [
            'annonces' => $annonces,
            'searchData' => $searchData
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONSULTANT')]
    public function new(
        Request $request,
        AnnonceRepository $annonceRepository,
        NewNotif $newNotif
    ): Response {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $annonce->setCreatedAt($date);

            /**
             * @var ?User $user
             */
            $user = $this->getUser();
            if ($this->isGranted('ROLE_ADMIN')) {
                $annonce->setAuthor($form->getData()->getAuthor());
            } else {
                $annonce->setAuthor($user->getConsultant());
            }
            $annonce->setPublicationStatus(1);
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce en ligne');
            $newNotif->notifNewAnnonce($annonce);

            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_CANDIDAT')]
    #[Route('/{id}/favorite', name: 'add_favorite', methods: ['GET'])]
    public function addToFavorite(
        Annonce $annonce,
        CandidatRepository $candidatRepository
    ): Response {

        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        if ($candidat->isInFavorite($annonce)) {
            $candidat->removeFromFavoriteOffer($annonce);
        } else {
            $candidat->addToFavoriteOffer($annonce);
        }
        $candidatRepository->save($candidat, true);

        $isInFavorite = $user instanceof User ? $user->getCandidat()->isInFavorite($annonce) : null;
        return $this->json([
            'isInFavorite' => $isInFavorite
        ]);
    }

    #[IsGranted('ROLE_CANDIDAT')]
    #[Route('/favorite', name: 'show_favorite', methods: ['GET'])]
    public function showFavorites(
        UserInterface $user,
        CandidatRepository $candidatRepository
    ): Response {
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        return $this->render('annonce/favorites.html.twig', [
            'fetchedAnnonces' => $candidat->getFavoriteOffers()
        ]);
    }

    #[Security("is_granted('ROLE_CANDIDAT') or is_granted('ROLE_CONSULTANT')")]
    #[Route('/company/{id}', name: 'show_by_company', methods: ['GET'])]
    public function showAnnonceByCompany(
        Company $company,
        AnnonceRepository $annonceRepository
    ): Response {

        return $this->render('annonce/favorites.html.twig', [
            'fetchedAnnonces' => $annonceRepository->findBy(
                [
                    'company' => $company
                ]
            )
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Request $request,
        Annonce $annonce,
        MessageRepository $messageRepository,
        RecruitmentProcessRepository $recruitProcessRepo,
        NotifRepository $notifRepository,
        UserRepository $userRepository
    ): Response {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        if (!is_null($user)) {
            foreach (
                $notifRepository->findBy(
                    ['wasRead' => false, 'user' => $user, 'type' => 'newAnnonce']
                ) as $notif
            ) {
                if ($notif->getAnnonce()->getId() == $annonce->getId()) {
                    $notif->setWasRead(true);
                    $notifRepository->save($notif, true);
                }
            }
            if (!$notifRepository->findBy(['wasRead' => false, 'user' => $user])) {
                $user->setHasNotifUnread(false);
                $userRepository->save($user, true);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $recruitmentProcess = new RecruitmentProcess();
            $recruitmentProcess->setStatus('Applied');
            $recruitmentProcess->setCandidat($user->getCandidat());
            $recruitmentProcess->setAnnonce($annonce);
            $recruitmentProcess->setExternaticConsultant($annonce->getAuthor());
            $recruitmentProcess->setReadByCandidat(true);
            $recruitmentProcess->setReadByConsultant(false);
            $recruitProcessRepo->save($recruitmentProcess, true);

            $message->setRecruitmentProcess($recruitmentProcess);

            $message->setSendBy($user);
            $message->setSendTo($annonce->getAuthor()->getUser());

            $messageRepository->save($message, true);

            $this->addFlash('success', 'Vous avez postulé !');
            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }

        $candidat = null;
        if (!is_null($user)) {
            $candidat = $user->getCandidat();
        }

        $recruProcessActuel = $recruitProcessRepo->findOneBy([
            "annonce" => $annonce,
            "candidat" => $candidat
        ]);

        return $this->renderForm('annonce/show.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            'recruProcessActuel' => $recruProcessActuel,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONSULTANT')]
    public function edit(Annonce $annonce, Request $request, AnnonceRepository $annonceRepository): response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var ?User $user
             */
            $user = $this->getUser();
            if ($this->isGranted('ROLE_ADMIN')) {
                $annonce->setAuthor($form->getData()->getAuthor());
            } else {
                $annonce->setAuthor($user->getConsultant());
            }
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce modifiée');
            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_CONSULTANT')]
    #[Route('/change-status/{id}/', name: 'change_status', methods: ["GET", "POST"])]
    public function changeAnnonceStatus(Annonce $annonce, AnnonceRepository $annonceRepository): response
    {
        if ($annonce->getPublicationStatus() == 0) {
            $annonce->setPublicationStatus(1);
        } else {
            $annonce->setPublicationStatus(0);
        }

        $annonceRepository->save($annonce, true);

        return $this->json([
            'isActivated' => $annonce->getPublicationStatus()
        ]);
    }
}
