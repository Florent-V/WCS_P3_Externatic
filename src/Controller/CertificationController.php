<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Entity\User;
use App\Form\CertificationType;
use App\Repository\CertificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/certification')]
class CertificationController extends AbstractController
{
    #[Route('/', name: 'app_certification_index', methods: ['GET'])]
    public function index(): Response
    {

        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $certifications = $user->getCandidat()->getCurriculum()->getCertifications();

        return $this->render('certification/index.html.twig', [
            'certifications' => $certifications,
        ]);
    }

    #[Route('/new', name: 'app_certification_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CertificationRepository $certificationRepo
    ): Response {

        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $curriculum = $user->getCandidat()->getCurriculum();

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
                ['_fragment' => 'certificationPanel']
            );
        }

        return $this->renderForm('certification/new.html.twig', [
            'certificationForm' => $certificationForm,
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
