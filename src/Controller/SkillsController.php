<?php

namespace App\Controller;

use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
use App\Repository\HardSkillRepository;
use App\Repository\SkillsRepository;
use App\Repository\SoftSkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/skills')]
class SkillsController extends AbstractController
{
    #[Route('/', name: 'app_skills_index', methods: ['GET'])]
    public function index(
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        SkillsRepository $skillsRepository,
        SoftSkillRepository $softSkillRepository,
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
        $softSkills = $softSkillRepository->findBy(
            ['skills' => $skills]
        );
        $hardSkills = $hardSkillRepository->findBy(
            ['skills' => $skills]
        );

        return $this->render('skills/index.html.twig', [
            'soft_skills' => $softSkills,
            'hard_skills' => $hardSkills,
        ]);
    }
}
