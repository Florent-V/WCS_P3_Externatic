<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Notif;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\NotifRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

#[route('/search', name: "search_")]
class AnnonceController extends AbstractController
{
    #[Route('/results', name: 'results')]
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $fetchedAnnonces = $annonceRepository->annonceFinder($request->get('form'));

        return $this->render('annonce/results.html.twig', [
            'fetchedAnnonces' => $fetchedAnnonces
        ]);
    }

    #[Route('/new', name: 'annonce_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        AnnonceRepository $annonceRepository,
        UserRepository $userRepository,
        NotifRepository $notificationRepository
    ): Response {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setCreatedAt(new DateTime('now'));
            $annonceRepository->save($annonce, true);
            $this->addFlash('success', 'Annonce en ligne');
            foreach ($userRepository->findByRole('ROLE_CANDIDAT') as $user) {
                $notification = new Notif();
                $notification->setContent('Une nouvelle annonce a été créer');
                $notification->setPath('search/' . $annonce->getId());
                $notification->setCreatedAt(new DateTime('now'));
                $notification->setUser($user);
                $notificationRepository->save($notification, true);
            }

            return $this->redirectToRoute('search_annonce_new');
        }
        return $this->renderForm('annonce/_form.html.twig', [
            'form' => $form,
            'annonce' => $annonce]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
