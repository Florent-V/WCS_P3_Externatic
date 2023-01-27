<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Form\AdminSearchType;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat')]
class CandidatController extends AbstractController
{
    #[Route('/', name: 'app_candidat_index', methods: ['GET'])]
    public function index(
        Request $request,
        CandidatRepository $candidatRepository,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $queryCandidats = $candidatRepository->findActiveCandidat($data['search']);
        } else {
            $queryCandidats = $candidatRepository->findActiveCandidat();
        }

        $candidats = $paginator->paginate(
            $queryCandidats,
            $request->query->getInt('page', 1),
            10
        );

        return $this->renderForm('admin/candidat/index.html.twig', [
            'candidats' => $candidats,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_candidat_edit', methods: ['GET', 'POST'])]
    public function editPro(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('admin_app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/update.html.twig', [
            'candidat' => $candidat,
            'candidatForm' => $form,
        ]);
    }
}
