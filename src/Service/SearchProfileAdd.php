<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\SearchProfileRepository;
use App\Repository\TechnoRepository;
use App\Entity\SearchProfile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchProfileAdd extends AbstractController
{
    private TechnoRepository $technoRepository;
    private SearchProfileRepository $searchProfileRepo;

    public function __construct(
        TechnoRepository $technoRepository,
        SearchProfileRepository $searchProfileRepo
    ) {
        $this->technoRepository = $technoRepository;
        $this->searchProfileRepo = $searchProfileRepo;
    }
    public function addToEntity(array $data, SearchProfile $searchProfile): void
    {

        if ($data['period']) {
            $searchProfile->setperiod($data['period']);
        }
        if ($data['company']) {
            $searchProfile->setcompanyId($data['company']);
        }
        if ($data['salaryMin']) {
            $searchProfile->setSalaryMin($data['salaryMin']);
        }
        if ($data['remote']) {
            $searchProfile->setRemote($data['remote']);
        }
        if ($data['techno']) {
            foreach ($data['techno'] as $techno) {
                $searchProfile->addTechno($this->technoRepository->findOneBy(['id' => $techno]));
            }
        }
        /**
         * @var ?User $user
         */
        $user = $this->getUser();
        $searchProfile->setCandidat($user->getCandidat());
        $this->searchProfileRepo->save($searchProfile, true);
    }
}
