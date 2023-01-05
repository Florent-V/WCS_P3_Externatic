<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
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

        $isInFavorite = '';
        if ($user instanceof User) {
            $isInFavorite = $user->getCandidat()->isInFavorite($annonce);
        }


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


    #[Route('/{id}', name: 'show')]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
