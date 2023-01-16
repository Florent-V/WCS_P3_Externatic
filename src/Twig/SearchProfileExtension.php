<?php

namespace App\Twig;

use App\Repository\CompanyRepository;
use App\Repository\TechnoRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SearchProfileExtension extends AbstractExtension
{
    public function __construct(
        private readonly TechnoRepository $technoRepository,
        private readonly CompanyRepository $companyRepository
    ) {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('technoName', [$this, 'showTechnoName']),
            new TwigFilter('companyName', [$this, 'showCompanyName']),
        ];
    }

    public function showTechnoName(int $id): string
    {
        $techno = $this->technoRepository->findOneBy(
            ['id' => $id]
        );
        return $techno->getName();
    }

    public function showCompanyName(int $id): string
    {
        $company = $this->companyRepository->findOneBy(
            ['id' => $id]
        );
        return $company->getName();
    }
}
