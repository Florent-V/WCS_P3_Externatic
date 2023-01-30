<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;
use App\Repository\CompanyRepository;
use App\Repository\RecruitmentProcessRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        AnnonceRepository $annonceRepository,
        CandidatRepository $candidatRepository,
        CompanyRepository $companyRepository,
    ): Response {
        $form = $this->createFormBuilder()
            ->add('searchQuery', TextType::class, [
                'label' => "Recherchez l'offre qui vont correspond !",
                'required' => false
                ])
            ->setAction($this->generateUrl('annonce_search_results'))
            ->setMethod('GET')
            ->getForm();

        $annoncesLandingPage = $annonceRepository->findBy([], ["createdAt" => "DESC"], 3);
        $nbAnnonce = $annonceRepository->countAnnonce();
        $nbCompany = $companyRepository->countCompany();
        $nbCandidat = $candidatRepository->countCandidat();
        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
            'annoncesLandingPage' => $annoncesLandingPage,
            'nbAnnonce' => $nbAnnonce,
            'nbCompany' => $nbCompany,
            'nbCandidat' => $nbCandidat
        ]);
    }
}
