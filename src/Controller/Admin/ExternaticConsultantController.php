<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\User;
use App\Form\CandidatType;
use App\Form\UserUpdateType;
use App\Repository\CandidatRepository;
use App\Repository\ExternaticConsultantRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consultant')]
class ExternaticConsultantController extends AbstractController
{
    #[Route('/index', name: 'app_consultant_index', methods: ['GET'])]
    public function index(
        ExternaticConsultantRepository $consultantRepository
    ): Response {

        return $this->render('admin/consultant/index.html.twig', [
            'consultants' => $consultantRepository->findAll(),
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

        return $this->renderForm('admin/candidat/edit.html.twig', [
            'candidat' => $candidat,
            'candidatForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultant_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $user->setEmail('user' . $user->getId() . '@deleted.fr');
            $user->setFirstname('firstname' . $user->getId());
            $user->setLastName('lastname' . $user->getId());
            $user->setIsActive(false);
            $userRepository->save($user, true);
        }

        return $this->redirectToRoute('admin_app_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
