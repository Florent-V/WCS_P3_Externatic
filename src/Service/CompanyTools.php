<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\ExternaticConsultant;
use Doctrine\ORM\EntityManagerInterface;

class CompanyTools
{
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    public function assign(
        Company $company,
        ExternaticConsultant $consultant,
    ): void {
        $company->setExternaticConsultant($consultant);
        $this->manager->persist($company);
        $this->manager->persist($consultant);
        foreach ($company->getAnnonces() as $annonce) {
            $annonce->setAuthor($consultant);
            $this->manager->persist($annonce);
            foreach ($annonce->getRecrutementProcesses() as $process) {
                $process->setExternaticConsultant($consultant);
                $this->manager->persist($process);
            }
        }
        $this->manager->flush();
    }

    public function disable(Company $company): void
    {
        $company->setIsActive(false);
        $this->manager->persist($company);
        foreach ($company->getAnnonces() as $annonce) {
            $annonce->setPublicationStatus(0);
            $this->manager->persist($annonce);
            foreach ($annonce->getRecrutementProcesses() as $process) {
                $process->setIsActive(false);
                $this->manager->persist($process);
            }
        }
        $this->manager->flush();
    }

    public function enable(Company $company): void
    {
        $company->setIsActive(true);
        $this->manager->persist($company);
        foreach ($company->getAnnonces() as $annonce) {
            $annonce->setPublicationStatus(1);
            $this->manager->persist($annonce);
            foreach ($annonce->getRecrutementProcesses() as $process) {
                $process->setIsActive(true);
                $this->manager->persist($process);
            }
        }
        $this->manager->flush();
    }
}
