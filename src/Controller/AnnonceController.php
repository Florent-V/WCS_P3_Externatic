<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[route('/search', name: "search_")]
class AnnonceController extends AbstractController
{
    #[Route('/results', name: 'results')]
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $fetchedAnnonces = is_null($request->get('form')['searchQuery']) ?
            $annonceRepository->annonceFinder("*") :
            $annonceRepository->annonceFinder($request->get('form')['searchQuery']);
        return $this->render('annonce/results.html.twig', [
            'fetchedAnnonces' => $fetchedAnnonces,
        ]);
    }
}
