<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/experience')]
class ExperienceController extends AbstractController
{
    #[Route('/', name: 'app_experience_index', methods: ['GET'])]
    public function index(
        ExperienceRepository $repository
    ): Response {

        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $experience = $repository->findFirstExperience($curriculum, false);

        if (!$experience) {
            return $this->render('candidat/oups.html.twig');
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
    }

    #[Route('/new', name: 'app_experience_new', methods: ['GET', 'POST'])]
    public function newExp(): Response
    {
        return $this->renderForm('experience/newExperience.html.twig');
    }

    #[Route('/{id}', name: 'app_experience_show', methods: ['GET'])]
    public function show(
        Experience $experience,
        ExperienceRepository $repository
    ): Response {

        if (
            $experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()
            || $experience->isIsFormation()
        ) {
            /**
             * @var ?User $user
             */
            $user = $this->getUser();
            $curriculum = $user->getCandidat()->getCurriculum();
            $experience = $repository->findFirstExperience($curriculum, false);

            return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
        }
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}/next', name: 'app_experience_next', methods: ['GET'])]
    public function next(ExperienceRepository $repository, int $id): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $nextExperience = $repository->findNextExperience($id, $curriculum, false);

        if (!$nextExperience) {
            $nextExperience = $repository->findFirstExperience($curriculum, false);
            $this->addFlash('info', 'You\'ve reached the end of your CVverse !');
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $nextExperience->getId()]);
    }

    #[Route('/{id}/prev', name: 'app_experience_previous', methods: ['GET'])]
    public function previous(ExperienceRepository $repository, int $id): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $previousExperience = $repository->findPreviousExperience($id, $curriculum, false);

        if (!$previousExperience) {
            $previousExperience = $repository->findLastExperience($curriculum, false);
            $this->addFlash('info', 'You\'ve reached the end of your CVverse !');
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $previousExperience->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Experience $experience,
        ExperienceRepository $experienceRepository
    ): Response {
        if (
            $experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()
            || $experience->isIsFormation()
        ) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Le serveur a compris la requête mais refuse de la satisfaire');
        }

        $experienceForm = $this->createForm(ExperienceType::class, $experience);
        $experienceForm->handleRequest($request);

        if ($experienceForm->isSubmitted() && $experienceForm->isValid()) {
            $experienceRepository->save($experience, true);

            return $this->redirectToRoute(
                'app_experience_show',
                ['id' => $experience->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('experience/editExperience.html.twig', [
            'experienceForm' => $experienceForm,
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}', name: 'app_experience_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Experience $experience,
        ExperienceRepository $experienceRepository
    ): Response {
        if ($experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can delete !');
        }
        if ($this->isCsrfTokenValid('delete' . $experience->getId(), $request->request->get('_token'))) {
            $experienceRepository->remove($experience, true);
        }

        return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
