<?php

namespace App\Controller;

use App\Entity\SearchProfile;
use App\Entity\User;
use App\Form\SearchProfileType;
use App\Repository\SearchProfileRepository;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search/profile')]
class SearchProfileController extends AbstractController
{
    #[Route('/', name: 'app_search_profile_index', methods: ['GET'])]
    public function index(SearchProfileRepository $searchProfileRepo): Response
    {
        return $this->render('search_profile/index.html.twig');
    }

    #[Route('/new', name: 'app_search_profile_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        SearchProfileRepository $searchProfileRepo,
        TechnoRepository $technoRepository,
    ): Response {

        $searchProfile = new SearchProfile();
        $data = json_decode($request->request->get('research'), true);
        $searchProfile->setSearchQuery($data);
        if ($data['searchQuery']){
            $searchProfile->setTitle($data['searchQuery']);
        }
        if ($data['workTime']){
            $searchProfile->setworkTime($data['workTime']);
        }
        if ($data['period']){
            $searchProfile->setperiod($data['period']);
        }
        if ($data['company']){
            $searchProfile->setcompanyId($data['company']);
        }
        if ($data['salaryMin']){
            $searchProfile->setSalaryMin($data['salaryMin']);
        }
        if ($data['remote']){
            $searchProfile->setRemote($data['remote']);
        }
//        if ($data['techno']){
//            foreach ($data['techno'] as $techno){
//                $searchProfile->addTechno($technoRepository->findOneBy(['id' => $techno]));
//            }
//        }
        if ($data['contractType']){
            foreach ($data['contractType'] as $contractType){

            }
        }
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $searchProfile->setCandidat($user->getCandidat());
        $searchProfileRepo->save($searchProfile, true);

        return $this->json([
            'result' => 'Recherche enregistrée !'
        ]);

//        return $this->redirectToRoute('annonce_search_results');
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
