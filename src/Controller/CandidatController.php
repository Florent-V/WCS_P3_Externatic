<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Form\UserUpdateType;
use App\Repository\CandidatRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/profile')]
class CandidatController extends AbstractController
{
    #[IsGranted('ROLE_CANDIDAT')]
    #[Route('/', name: 'app_candidat_profile', methods: ['GET'])]
    public function profile(CandidatRepository $candidatRepository): Response
    {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        return $this->render('candidat/profile.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[IsGranted('ROLE_CANDIDAT')]
    #[Route('/complete', name: 'app_candidat_complete', methods: ['GET', 'POST'])]
    public function complete(
        Request $request,
        UserInterface $user,
        CandidatRepository $candidatRepository,
        UserRepository $userRepository
    ): Response {

        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        $candidatForm = $this->createForm(CandidatType::class, $candidat);
        $candidatForm->handleRequest($request);

        $userForm = $this->createForm(UserUpdateType::class, $user);
        $userForm->handleRequest($request);

        if ($candidatForm->isSubmitted() && $candidatForm->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/complete.html.twig', [
            'candidat' => $candidat,
            'candidatForm' => $candidatForm,
            'userForm' => $userForm,
        ]);
    }



    #[Route('/index', name: 'app_candidat_index', methods: ['GET'])]
    public function index(CandidatRepository $candidatRepository): Response
    {
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CandidatRepository $candidatRepository): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_delete', methods: ['POST'])]
    public function delete(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidat->getId(), $request->request->get('_token'))) {
            $candidatRepository->remove($candidat, true);
        }

        return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
