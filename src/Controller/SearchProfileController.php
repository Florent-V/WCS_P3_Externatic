<?php

namespace App\Controller;

use App\Entity\SearchProfile;
use App\Entity\User;
use App\Form\SearchProfileType;
use App\Repository\SearchProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search/profile')]
class SearchProfileController extends AbstractController
{
    #[Route('/', name: 'app_search_profile_index', methods: ['GET'])]
    public function index(SearchProfileRepository $searchProfileRepo): Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $candidat = $user->getCandidat();
        $searchProfiles = $searchProfileRepo->findBy(
            ['candidat' => $candidat]
        );

        $results = [];
        foreach ($searchProfiles as $profile) {
            $results[] = json_decode($profile->getSearchQuery());
        }

        return $this->render('search_profile/index.html.twig', [
            'results' => $results,
            'searchProfiles' => $searchProfiles
        ]);
    }

    #[Route('/new', name: 'app_search_profile_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        SearchProfileRepository $searchProfileRepo,
    ): Response {

        $searchProfile = new SearchProfile();
        $searchProfile->setSearchQuery($request->request->get('research'));
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $searchProfile->setCandidat($user->getCandidat());
        $searchProfileRepo->save($searchProfile, true);
        //dd(json_decode($request->request->get('research')));
//        $searchProfile = new SearchProfile();
//        $form = $this->createForm(SearchProfileType::class, $searchProfile);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $searchProfileRepo->save($searchProfile, true);
//
//            return $this->redirectToRoute('app_search_profile_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('search_profile/new.html.twig', [
//            'search_profile' => $searchProfile,
//            'form' => $form,
//        ]);

        return $this->redirectToRoute('annonce_search_results');
    }

    #[Route('/{id}', name: 'app_search_profile_show', methods: ['GET'])]
    public function show(SearchProfile $searchProfile): Response
    {
        return $this->render('search_profile/show.html.twig', [
            'search_profile' => $searchProfile,
        ]);
    }


    #[Route('/{id}', name: 'app_search_profile_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        SearchProfile $searchProfile,
        SearchProfileRepository $searchProfileRepo
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $searchProfile->getId(), $request->request->get('_token'))) {
            $searchProfileRepo->remove($searchProfile, true);
        }

        return $this->redirectToRoute('app_search_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
