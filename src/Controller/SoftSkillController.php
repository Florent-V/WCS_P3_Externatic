<?php

namespace App\Controller;

use App\Entity\SoftSkill;
use App\Form\SoftSkillType;
use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
use App\Repository\SkillsRepository;
use App\Repository\SoftSkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/soft/skill')]
class SoftSkillController extends AbstractController
{
    #[Route('/new', name: 'app_soft_skill_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        SkillsRepository $skillsRepository,
        SoftSkillRepository $softSkillRepository,
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

        $softSkill = new SoftSkill();
        $softSkillForm = $this->createForm(SoftSkillType::class, $softSkill);
        $softSkillForm->handleRequest($request);

        if ($softSkillForm->isSubmitted() && $softSkillForm->isValid()) {
            $softSkill->setSkills($skills);
            $softSkillRepository->save($softSkill, true);

            return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soft_skill/new.html.twig', [
            'soft_skill' => $softSkill,
            'softSkillForm' => $softSkillForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soft_skill_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        SoftSkill $softSkill,
        SoftSkillRepository $softSkillRepository
    ): Response {
        if ($softSkill->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit !');
        }
        $softSkillForm = $this->createForm(SoftSkillType::class, $softSkill);
        $softSkillForm->handleRequest($request);

        if ($softSkillForm->isSubmitted() && $softSkillForm->isValid()) {
            $softSkillRepository->save($softSkill, true);

            return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('soft_skill/edit.html.twig', [
            'soft_skill' => $softSkill,
            'softSkillForm' => $softSkillForm,
        ]);
    }

    #[Route('/{id}', name: 'app_soft_skill_delete', methods: ['POST'])]
    public function delete(Request $request, SoftSkill $softSkill, SoftSkillRepository $softSkillRepository): Response
    {
        if ($softSkill->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }

        if ($this->isCsrfTokenValid('delete' . $softSkill->getId(), $request->request->get('_token'))) {
            $softSkillRepository->remove($softSkill, true);
        }

        return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
    }
}
