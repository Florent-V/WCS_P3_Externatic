<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
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
    public function index(CertificationRepository $certificationRepo): Response
    {
        return $this->render('certification/index.html.twig', [
            'certifications' => $certificationRepo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_certification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CertificationRepository $certificationRepo): Response
    {
        $certification = new Certification();
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certificationRepo->save($certification, true);

            return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certification/new.html.twig', [
            'certification' => $certification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_certification_show', methods: ['GET'])]
    public function show(Certification $certification): Response
    {
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
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certificationRepo->save($certification, true);

            return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certification/edit.html.twig', [
            'certification' => $certification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_certification_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Certification $certification,
        CertificationRepository $certificationRepo
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $certification->getId(), $request->request->get('_token'))) {
            $certificationRepo->remove($certification, true);
        }

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }
}
