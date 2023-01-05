<?php

namespace App\Controller;

use App\Entity\RecruitmentProcess;
use App\Form\RecruitmentProcessType;
use App\Repository\RecruitmentProcessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recruitment/process')]
class RecruitmentProcessController extends AbstractController
{
    #[Route('/', name: 'app_recruitment_process_index', methods: ['GET'])]
    public function index(RecruitmentProcessRepository $recruitmentProcRepo): Response
    {
        return $this->render('recruitment_process/index.html.twig', [
            'recruitment_processes' => $recruitmentProcRepo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recruitment_process_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecruitmentProcessRepository $recruitmentProcRepo): Response
    {
        $recruitmentProcess = new RecruitmentProcess();
        $form = $this->createForm(RecruitmentProcessType::class, $recruitmentProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruitmentProcRepo->save($recruitmentProcess, true);

            return $this->redirectToRoute('app_recruitment_process_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recruitment_process/new.html.twig', [
            'recruitment_process' => $recruitmentProcess,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruitment_process_show', methods: ['GET'])]
    public function show(RecruitmentProcess $recruitmentProcess): Response
    {
        return $this->render('recruitment_process/show.html.twig', [
            'recruitment_process' => $recruitmentProcess,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recruitment_process_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        RecruitmentProcess $recruitmentProcess,
        RecruitmentProcessRepository $recruitmentProcRepo
    ): Response {
        $form = $this->createForm(RecruitmentProcessType::class, $recruitmentProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruitmentProcRepo->save($recruitmentProcess, true);

            return $this->redirectToRoute('app_recruitment_process_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recruitment_process/edit.html.twig', [
            'recruitment_process' => $recruitmentProcess,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruitment_process_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        RecruitmentProcess $recruitmentProcess,
        RecruitmentProcessRepository $recruitmentProcRepo
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $recruitmentProcess->getId(), $request->request->get('_token'))) {
            $recruitmentProcRepo->remove($recruitmentProcess, true);
        }

        return $this->redirectToRoute('app_recruitment_process_index', [], Response::HTTP_SEE_OTHER);
    }
}
