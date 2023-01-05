<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Form\CertificationType;
use App\Repository\CandidatRepository;
use App\Repository\CertificationRepository;
use App\Repository\CurriculumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/certification')]
class CertificationController extends AbstractController
{
    #[Route('/', name: 'app_certification_index', methods: ['GET'])]
    public function index(
        CertificationRepository $certificationRepo,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );

        $certifications = $certificationRepo->findBy(
            ['curriculum' => $curriculum]
        );

        return $this->render('certification/index.html.twig', [
            'certifications' => $certifications,
        ]);
    }

    #[Route('/new', name: 'app_certification_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        CertificationRepository $certificationRepo
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );

        //Certification Entity &  Form
        $certification = new Certification();
        $certificationForm = $this->createForm(CertificationType::class, $certification);
        $certificationForm->handleRequest($request);

        //Validation Form Certification Data
        if ($certificationForm->isSubmitted() && $certificationForm->isValid()) {
            $certification->setCurriculum($curriculum);
            $certificationRepo->save($certification, true);

            return $this->redirectToRoute(
                'app_candidat_profile',
                ['candidat' => $candidat,],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('certification/new.html.twig', [
            'certificationForm' => $certificationForm,
        ]);
    }

    #[Route('/{id}', name: 'app_certification_show', methods: ['GET'])]
    public function show(Certification $certification): Response
    {
        if ($certification->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }
        return $this->render('certification/show.html.twig', [
            'certification' => $certification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_certification_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Certification $certification,
        CertificationRepository $certificationRepo
    ): Response {

        if ($certification->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }

        $certificationForm = $this->createForm(CertificationType::class, $certification);
        $certificationForm->handleRequest($request);

        if ($certificationForm->isSubmitted() && $certificationForm->isValid()) {
            $certificationRepo->save($certification, true);

            return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certification/edit.html.twig', [
            'certificationForm' => $certificationForm,
            'certification' => $certification,
        ]);
    }

    #[Route('/{id}', name: 'app_certification_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Certification $certification,
        CertificationRepository $certificationRepo
    ): Response {
        if ($certification->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }

        if ($this->isCsrfTokenValid('delete' . $certification->getId(), $request->request->get('_token'))) {
            $certificationRepo->remove($certification, true);
        }

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }
}
