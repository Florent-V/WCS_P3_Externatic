<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CandidatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/{id}/favorite', name:'app_company_add_favorite', methods: ['GET'])]
    public function addToFavorite(
        Company $company,
        CandidatRepository $candidatRepository
    ): Response {

        $user = $this->getUser();
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        if ($candidat->isCompanyFavorite($company)) {
            $candidat->removeCompanyFromFavorite($company);
        } else {
            $candidat->addCompanyToFavorite($company);
        }
        $candidatRepository->save($candidat, true);

        $isInFavorite = $user instanceof User ? $user->getCandidat()->isCompanyFavorite($company) : null;
        return $this->json([
            'isInFavorite' => $isInFavorite
        ]);
    }

    #[Route('/favorite', name:'app_company_show_favorite', methods: ['GET'])]
    public function showFavorites(
        UserInterface $user,
        CandidatRepository $candidatRepository
    ): Response {
        $candidat = $candidatRepository->findOneBy(
            ['user' => $user]
        );

        return $this->render('company/favorites.html.twig', [
            'fetchedCompanies' => $candidat->getFavoriteCompanies()
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }
}
