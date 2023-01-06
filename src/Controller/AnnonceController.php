<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\RecruitmentProcess;
use App\Form\AnnonceType;
use App\Form\MessageType;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
use DateTime;
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
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $fetchedAnnonces = $annonceRepository->annonceFinder($request->get('form'));

        return $this->render('annonce/results.html.twig', [
            'fetchedAnnonces' => $fetchedAnnonces
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONSULTANT')]
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $annonce->setCreatedAt($date);
            //$annonce->setAuthor();
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce en ligne');
        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            ]);
    }

    #[Route('/{id}/favorite', name:'add_favorite', methods: ['GET'])]
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

    #[Route('/favorite', name:'show_favorite', methods: ['GET'])]
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


    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Request $request,
        Annonce $annonce,
        MessageRepository $messageRepository,
        RecruitmentProcessRepository $recruitProcessRepo
    ): Response {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        /**
         * @var ?User $user
         */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();

            $recruitmentProcess = new RecruitmentProcess();
            $recruitmentProcess->setStatus('Applied');
            $recruitmentProcess->setCandidat($user->getCandidat());
            $recruitmentProcess->setCreatedAt($date);
            $recruitmentProcess->setAnnonce($annonce);
            $recruitProcessRepo->save($recruitmentProcess, true);

            $message->setRecruitmentProcess($recruitmentProcess);

            $message->setSendBy($user);
            $message->setSendTo($annonce->getAuthor()->getUser());
            $message->setDate($date);

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
}
