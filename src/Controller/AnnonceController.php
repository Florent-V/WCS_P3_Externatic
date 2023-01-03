<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            'annonce' => $annonce,
            'form' => $form,
            ]);
    }

    #[Route('/{id}/favorite', name:'favorite', methods: ['GET'])]
    public function addToFavorite(
        Annonce $annonce,
        EntityManagerInterface $manager,
        CandidatRepository $candidatRepository
    ): Response {
        if (!$annonce) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        if ($candidat->isInFavorite($annonce)) {
            $candidat->removeFromFavoriteOffer($annonce);
        } else {
            $candidat->addToFavoriteOffer($annonce);
        }
        $manager->flush();
    }

    #[Route('/{id}', name: 'show')]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
