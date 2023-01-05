<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Message;
use App\Entity\RecruitmentProcess;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Form\MessageType;
use App\Repository\AnnonceRepository;
use App\Repository\MessageRepository;
use App\Repository\RecruitmentProcessRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce en ligne');
        }
        return $this->renderForm('annonce/_form.html.twig', [
            'form' => $form,
            'annonce' => $annonce]);
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
         * @var User $user
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


        $candidat = $user->getCandidat() ?: null;
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
