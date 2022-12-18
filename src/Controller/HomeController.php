<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $annoncesLandingPage = $annonceRepository->findBy([], ["createdAt" => "DESC"], 3);
        return $this->render('home/index.html.twig', [
            'annoncesLandingPage' => $annoncesLandingPage
        ]);
    }
}
