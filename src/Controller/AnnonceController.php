<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use function PHPUnit\Framework\isEmpty;

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
}
