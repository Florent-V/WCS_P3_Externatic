<?php

namespace App\Controller;

use App\Entity\HardSkill;
use App\Form\HardSkillType;
use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
use App\Repository\HardSkillRepository;
use App\Repository\SkillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hard/skill')]
class HardSkillController extends AbstractController
{
    #[Route('/new', name: 'app_hard_skill_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        SkillsRepository $skillsRepository,
        HardSkillRepository $hardSkillRepository
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

        $hardSkill = new HardSkill();
        $hardSkillForm = $this->createForm(HardSkillType::class, $hardSkill);
        $hardSkillForm->handleRequest($request);

        if ($hardSkillForm->isSubmitted() && $hardSkillForm->isValid()) {
            $hardSkill->setSkills($skills);
            $hardSkillRepository->save($hardSkill, true);

            return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hard_skill/new.html.twig', [
            'hard_skill' => $hardSkill,
            'hardSkillForm' => $hardSkillForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hard_skill_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        HardSkill $hardSkill,
        HardSkillRepository $hardSkillRepository
    ): Response {

        if ($hardSkill->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit !');
        }

        $hardSkillForm = $this->createForm(HardSkillType::class, $hardSkill);
        $hardSkillForm->handleRequest($request);

        if ($hardSkillForm->isSubmitted() && $hardSkillForm->isValid()) {
            $hardSkillRepository->save($hardSkill, true);

            return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hard_skill/edit.html.twig', [
            'hard_skill' => $hardSkill,
            'hardSkillForm' => $hardSkillForm,
        ]);
    }

    #[Route('/{id}', name: 'app_hard_skill_delete', methods: ['POST'])]
    public function delete(Request $request, HardSkill $hardSkill, HardSkillRepository $hardSkillRepository): Response
    {
        if ($hardSkill->getSkills()->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit !');
        }

        if ($this->isCsrfTokenValid('delete' . $hardSkill->getId(), $request->request->get('_token'))) {
            $hardSkillRepository->remove($hardSkill, true);
        }

        return $this->redirectToRoute('app_candidat_profile', [], Response::HTTP_SEE_OTHER);
    }
}
