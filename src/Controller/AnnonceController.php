<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[route('/search', name: "search_")]
class AnnonceController extends AbstractController
{
    #[Route('/results', name: 'results')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();
        return $this->render('annonce/results.html.twig', [
            'annonces' => $annonces,
        ]);
    }
}