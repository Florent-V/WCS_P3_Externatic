<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Message;
use App\Entity\User;
use App\Form\SpontaneType;
use App\Repository\MessageRepository;
use App\Form\CompanyType;
use App\Repository\CandidatRepository;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;


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
    public function showFavorites(): Response
    {
        return $this->render('company/favorites.html.twig');
    }


    #[Route('/{id}', name: 'app_company_show', methods: ['GET', 'POST'])]
    public function show(Company $company, Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(SpontaneType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSendTo($company->getExternaticConsultant()->getUser());
            /**
             * @var ?User $user
             */
            $user = $this->getUser();
            $message->setSendBy($user);

            $date = new DateTime();
            $message->setDate($date);
            $messageRepository->save($message, true);
            $this->addFlash('success', 'Vous avez postulé !');
            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }
        return $this->renderForm('company/show.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }
}
