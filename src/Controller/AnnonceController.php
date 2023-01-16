<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Notif;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

use function PHPUnit\Framework\isEmpty;

#[route('/annonce', name: "annonce_")]
class AnnonceController extends AbstractController
{
    #[Route('/search/results', name: 'search_results')]
    public function index(
        Request $request,
        AnnonceRepository $annonceRepository,
        PaginatorInterface $paginator
    ): Response {
        $fetchedAnnonces = $annonceRepository->annonceFinder($request->get('form'));

        $annonces = $paginator->paginate(
            $fetchedAnnonces,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('annonce/results.html.twig', [
            'annonces' => $annonces
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
            if (in_array("ROLE_ADMIN", $user->getRoles())) {
                $annonce->setAuthor($user->getConsultant());
            } else {
                $annonce->setAuthor($user->getConsultant());
            }

            //A SUPPRIMER IMPERATIVEMENT
            $annonce->setContractType("CDD");

            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce en ligne');
            $newNotif->newNotifAnnonce($annonce);

            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }


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

    #[Route('/company/{id}', name: 'show_by_company', methods: ['GET'])]
    public function showAnnonceByCompany(
        UserInterface $user,
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
        NotifRepository $notifRepository
    ): Response {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        if (!is_null($user)) {
            foreach ($user->getNotifications() as $notif) {
                if ($notif->getParameter() == $annonce->getId()) {
                    $notif->setWasRead(true);
                    $notifRepository->save($notif, true);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $recruitmentProcess = new RecruitmentProcess();
            $recruitmentProcess->setStatus('Applied');
            $recruitmentProcess->setCandidat($user->getCandidat());
            $recruitmentProcess->setAnnonce($annonce);
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
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce modifiée');
            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }
}
