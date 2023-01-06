<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/experience')]
class ExperienceController extends AbstractController
{
    #[Route('/index', name: 'app_experience_index', methods: ['GET'])]
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('admin/candidat/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_experience_show', methods: ['GET'])]
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        $experienceForm = $this->createForm(ExperienceType::class, $experience);
        $experienceForm->handleRequest($request);

        if ($experienceForm->isSubmitted() && $experienceForm->isValid()) {
            $experienceRepository->save($experience, true);

            return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/edit.html.twig', [
            'experience' => $experience,
            'experienceForm' => $experienceForm,
        ]);
    }

    #[Route('/{id}', name: 'app_experience_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Experience $experience,
        ExperienceRepository $experienceRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $experience->getId(), $request->request->get('_token'))) {
            $experienceRepository->remove($experience, true);
        }
        return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
