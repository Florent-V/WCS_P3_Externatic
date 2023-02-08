<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\FormationType;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(
        ExperienceRepository $repository
    ): Response {

        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $experience = $repository->findFirstExperience($curriculum, true);

        if (!$experience) {
            return $this->render('candidat/oups.html.twig');
        }

        return $this->redirectToRoute('app_formation_show', ['id' => $experience->getId()]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function newForm(): Response
    {
        return $this->renderForm('experience/newFormation.html.twig');
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(
        Experience $experience,
        ExperienceRepository $repository
    ): Response {

        if (
            $experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()
            || !$experience->isIsFormation()
        ) {
            /**
             * @var ?User $user
             */
            $user = $this->getUser();
            $curriculum = $user->getCandidat()->getCurriculum();
            $experience = $repository->findFirstExperience($curriculum, true);

            return $this->redirectToRoute('app_formation_show', ['id' => $experience->getId()]);
        }
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}/next', name: 'app_formation_next', methods: ['GET'])]
    public function next(ExperienceRepository $repository, int $id): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $nextExperience = $repository->findNextExperience($id, $curriculum, true);

        if (!$nextExperience) {
            $nextExperience = $repository->findFirstExperience($curriculum, true);
            $this->addFlash('info', 'You\'ve reached the end of your CVverse !');
        }

        return $this->redirectToRoute('app_formation_show', ['id' => $nextExperience->getId()]);
    }

    #[Route('/{id}/prev', name: 'app_formation_previous', methods: ['GET'])]
    public function previous(ExperienceRepository $repository, int $id): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();
        $previousExperience = $repository->findPreviousExperience($id, $curriculum, true);

        if (!$previousExperience) {
            $previousExperience = $repository->findLastExperience($curriculum, true);
            $this->addFlash('info', 'You\'ve reached the end of your CVverse !');
        }

        return $this->redirectToRoute('app_formation_show', ['id' => $previousExperience->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Experience $experience,
        ExperienceRepository $experienceRepository
    ): Response {
        if ($experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit !');
        }

            $formationForm = $this->createForm(FormationType::class, $experience);
            $formationForm->handleRequest($request);

        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $experienceRepository->save($experience, true);

            return $this->redirectToRoute(
                'app_formation_show',
                ['id' => $experience->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

            return $this->renderForm('experience/editFormation.html.twig', [
                'formationForm' => $formationForm,
                'experience' => $experience,
            ]);
    }
}
