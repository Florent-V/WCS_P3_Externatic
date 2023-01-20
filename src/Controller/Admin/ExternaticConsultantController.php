<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\Curriculum;
use App\Entity\ExternaticConsultant;
use App\Entity\Skills;
use App\Entity\User;
use App\Form\CandidatType;
use App\Form\RegistrationFormType;
use App\Form\UserUpdateType;
use App\Repository\CandidatRepository;
use App\Repository\ExternaticConsultantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
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
}
