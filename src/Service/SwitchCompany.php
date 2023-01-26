<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\ExternaticConsultant;
use Doctrine\ORM\EntityManagerInterface;

class SwitchCompany
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
}
