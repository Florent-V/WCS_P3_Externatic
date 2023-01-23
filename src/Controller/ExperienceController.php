<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\ExperienceType;
use App\Form\FormationType;
use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
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
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        ExperienceRepository $experienceRepository
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );

        $experiences = $experienceRepository->findBy(
            ['curriculum' => $curriculum]
        );

        return $this->render('experience/index.html.twig', [
            'user' => $user,
            'curriculum' => $curriculum,
            'candidat' => $candidat,
            'experiences' => $experiences,
        ]);
    }

    #[Route('/new/{type}', name: 'app_experience_new', methods: ['GET', 'POST'])]
    public function newExp(
        string $type,
    ): Response {

//
        if ('experience' === $type) {
            return $this->renderForm('experience/newExperience.html.twig');
        } elseif ('formation' === $type) {
            return $this->renderForm('experience/newFormation.html.twig');
        } else {
            throw $this->createNotFoundException('The page doesn\'t exist');
        }
    }

    #[Route('/{id}', name: 'app_experience_show', methods: ['GET'])]
    public function show(Experience $experience): Response
    {
        if ($experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
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
        $nextExperience = $repository->findNextExperience($id, $curriculum);

        if (!$nextExperience) {
            throw $this->createNotFoundException('Il n\'y a pas d\'expérience suivante');
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
        $previousExperience = $repository->findPreviousExperience($id, $curriculum);

        if (!$previousExperience) {
            throw $this->createNotFoundException('Il n\'y a pas d\'expérience précédente');
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $previousExperience->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Experience $experience,
        ExperienceRepository $experienceRepository
    ): Response {
        if ($experience->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit !');
        }

        if ($experience->isIsFormation()) {
            $formationForm = $this->createForm(FormationType::class, $experience);
            $formationForm->handleRequest($request);

            if ($formationForm->isSubmitted() && $formationForm->isValid()) {
                $experienceRepository->save($experience, true);

                return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('experience/editFormation.html.twig', [
                'formationForm' => $formationForm,
                'experience' => $experience,
            ]);
        } else {
            $experienceForm = $this->createForm(ExperienceType::class, $experience);
            $experienceForm->handleRequest($request);

            if ($experienceForm->isSubmitted() && $experienceForm->isValid()) {
                $experienceRepository->save($experience, true);

                return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('experience/editExperience.html.twig', [
                'experienceForm' => $experienceForm,
                'experience' => $experience,
            ]);
        }
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
