<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Experience;
use App\Form\CandidatType;
use App\Form\ExperienceType;
use App\Form\FormationType;
use App\Form\UserUpdateType;
use App\Repository\CandidatRepository;
use App\Repository\ExperienceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/profile')]
class CandidatController extends AbstractController
{
    #[Route('/', name: 'app_candidat_profile', methods: ['GET'])]
    public function profile(CandidatRepository $candidatRepository): Response
    {
        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        return $this->render('candidat/profile.html.twig', [
            'candidat' => $candidat,
            'user' => $user,
        ]);
    }

    #[Route('/complete', name: 'app_candidat_complete', methods: ['GET', 'POST'])]
    public function complete(
        Request $request,
        UserInterface $user,
        CandidatRepository $candidatRepository,
        UserRepository $userRepository,
        ExperienceRepository $experienceRepository
    ): Response {

        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );
        $user = $candidat->getUser();

        $experience = new Experience();

        $formation = new Experience();

        $candidatForm = $this->createForm(CandidatType::class, $candidat);
        $candidatForm->handleRequest($request);

        $userForm = $this->createForm(UserUpdateType::class, $user);
        $userForm->handleRequest($request);

        $experienceForm = $this->createForm(ExperienceType::class, $experience);
        $experienceForm->handleRequest($request);

        $formationForm = $this->createForm(FormationType::class, $formation);
        $formationForm->handleRequest($request);


        if ($candidatForm->isSubmitted() && $candidatForm->isValid()) {
            $candidatRepository->save($candidat, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        if ($experienceForm->isSubmitted() && $experienceForm->isValid()) {
            $experience->setIsFormation(false);
            $experience->setCandidat($candidat);
            $experienceRepository->save($experience, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $formation->setIsFormation(true);
            $formation->setCandidat($candidat);
            $experienceRepository->save($formation, true);

            return $this->redirectToRoute('app_candidat_profile', ['candidat' => $candidat,], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/complete.html.twig', [
            'candidat' => $candidat,
            'candidatForm' => $candidatForm,
            'userForm' => $userForm,
            'experienceForm' => $experienceForm,
            'formationForm' => $formationForm
        ]);
    }
}
