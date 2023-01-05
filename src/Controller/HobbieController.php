<?php

namespace App\Controller;

use App\Entity\Hobbie;
use App\Form\HobbieType;
use App\Repository\CandidatRepository;
use App\Repository\CurriculumRepository;
use App\Repository\HobbieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hobbie')]
class HobbieController extends AbstractController
{
    #[Route('/', name: 'app_hobbie_index', methods: ['GET'])]
    public function index(
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        HobbieRepository $hobbieRepository
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );
        $hobbies = $hobbieRepository->findBy(
            ['curriculum' => $curriculum]
        );

        return $this->render('hobbie/index.html.twig', [
            'hobbies' => $hobbies,
        ]);
    }

    #[Route('/new', name: 'app_hobbie_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CandidatRepository $candidatRepository,
        CurriculumRepository $curriculumRepository,
        HobbieRepository $hobbieRepository
    ): Response {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $curriculum = $curriculumRepository->findOneBy(
            ['candidat' => $candidat]
        );

        $hobbie = new Hobbie();
        $hobbieForm = $this->createForm(HobbieType::class, $hobbie);
        $hobbieForm->handleRequest($request);

        if ($hobbieForm->isSubmitted() && $hobbieForm->isValid()) {
            $hobbie->setCurriculum($curriculum);
            $hobbieRepository->save($hobbie, true);

            return $this->redirectToRoute('app_hobbie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hobbie/new.html.twig', [
            'hobbie' => $hobbie,
            'hobbieForm' => $hobbieForm,
        ]);
    }

    #[Route('/{id}', name: 'app_hobbie_show', methods: ['GET'])]
    public function show(Hobbie $hobbie): Response
    {
        if ($hobbie->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can consult !');
        }
        return $this->render('hobbie/show.html.twig', [
            'hobbie' => $hobbie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hobbie_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Hobbie $hobbie,
        HobbieRepository $hobbieRepository
    ): Response {
        if ($hobbie->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit  !');
        }
        $hobbieForm = $this->createForm(HobbieType::class, $hobbie);
        $hobbieForm->handleRequest($request);

        if ($hobbieForm->isSubmitted() && $hobbieForm->isValid()) {
            $hobbieRepository->save($hobbie, true);

            return $this->redirectToRoute('app_hobbie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hobbie/edit.html.twig', [
            'hobbie' => $hobbie,
            'hobbieForm' => $hobbieForm,
        ]);
    }

    #[Route('/{id}', name: 'app_hobbie_delete', methods: ['POST'])]
    public function delete(Request $request, Hobbie $hobbie, HobbieRepository $hobbieRepository): Response
    {
        if ($hobbie->getCurriculum()->getCandidat()->getUser() !== $this->getUser()) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can delete !');
        }
        if ($this->isCsrfTokenValid('delete' . $hobbie->getId(), $request->request->get('_token'))) {
            $hobbieRepository->remove($hobbie, true);
        }

        return $this->redirectToRoute('app_hobbie_index', [], Response::HTTP_SEE_OTHER);
    }
}
