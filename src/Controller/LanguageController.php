<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
use App\Repository\LanguageRepository;
use App\Repository\SkillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/language')]
class LanguageController extends AbstractController
{
    #[Route('/', name: 'app_language_index', methods: ['GET'])]
    public function index(
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        SkillsRepository $skillsRepository,
        LanguageRepository $languageRepository
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );
        $skills = $skillsRepository->findOneBy(
            ['curriculum' => $curriculum]
        );

        $languages = $languageRepository->findBy(
            ['skills' => $skills]
        );

        return $this->render('language/index.html.twig', [
            'languages' => $languages,
        ]);
    }

    #[Route('/new', name: 'app_language_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        SkillsRepository $skillsRepository,
        LanguageRepository $languageRepository
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );
        $skills = $skillsRepository->findOneBy(
            ['curriculum' => $curriculum]
        );
        $language = new Language();
        $languageForm = $this->createForm(LanguageType::class, $language);
        $languageForm->handleRequest($request);

        if ($languageForm->isSubmitted() && $languageForm->isValid()) {
            $language->setSkills($skills);
            $languageRepository->save($language, true);

            return $this->redirectToRoute('app_language_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('language/new.html.twig', [
            'language' => $language,
            'languageForm' => $languageForm,
        ]);
    }

    #[Route('/{id}', name: 'app_language_show', methods: ['GET'])]
    public function show(Language $language): Response
    {
        if ($language->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }
        return $this->render('language/show.html.twig', [
            'language' => $language,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_language_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Language $language,
        LanguageRepository $languageRepository
    ): Response {
        if ($language->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can Edit !');
        }
        $languageForm = $this->createForm(LanguageType::class, $language);
        $languageForm->handleRequest($request);

        if ($languageForm->isSubmitted() && $languageForm->isValid()) {
            $languageRepository->save($language, true);

            return $this->redirectToRoute('app_language_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('language/edit.html.twig', [
            'language' => $language,
            'languageForm' => $languageForm,
        ]);
    }

    #[Route('/{id}', name: 'app_language_delete', methods: ['POST'])]
    public function delete(Request $request, Language $language, LanguageRepository $languageRepository): Response
    {
        if ($language->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can Delete !');
        }
        if ($this->isCsrfTokenValid('delete' . $language->getId(), $request->request->get('_token'))) {
            $languageRepository->remove($language, true);
        }

        return $this->redirectToRoute('app_language_index', [], Response::HTTP_SEE_OTHER);
    }
}
