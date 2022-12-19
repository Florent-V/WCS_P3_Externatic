<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('searchQuery', TextType::class, [
                'label' => "Recherchez l'offre qui vont correspond !",
                'required' => false
                ])
            ->setAction($this->generateUrl('search_results'))
            ->setMethod('GET')
            ->getForm();

        $annoncesLandingPage = $annonceRepository->findBy([], ["createdAt" => "DESC"], 3);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'annoncesLandingPage' => $annoncesLandingPage
        ]);
    }
}
