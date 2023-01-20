<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\User;
use App\Form\CandidatType;
use App\Form\UserUpdateType;
use App\Repository\CandidatRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat')]
class CandidatController extends AbstractController
{
    #[Route('/index', name: 'app_candidat_index', methods: ['GET'])]
    public function index(
        CandidatRepository $candidatRepository
    ): Response {

        return $this->render('admin/candidat/index.html.twig', [
            'candidats' => $candidatRepository->findActiveCandidat(),
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('admin/candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidat_edit', methods: ['GET', 'POST'])]
    public function editPro(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('admin_app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/update.html.twig', [
            'candidat' => $candidat,
            'candidatForm' => $form,
        ]);
    }
}
