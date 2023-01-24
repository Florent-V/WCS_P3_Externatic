<?php

namespace App\Entity;

use App\Repository\ExternaticConsultantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExternaticConsultantRepository::class)]
class ExternaticConsultant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'consultant', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'externaticConsultant', targetEntity: Company::class)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Annonce::class, orphanRemoval: true)]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'consultant', targetEntity: Appointement::class)]
    private Collection $appointements;

    #[ORM\OneToMany(mappedBy: 'externaticConsultant', targetEntity: RecruitmentProcess::class)]
    private Collection $recruitmentProcesses;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->appointements = new ArrayCollection();
        $this->recruitmentProcesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setExternaticConsultant($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getExternaticConsultant() === $this) {
                $company->setExternaticConsultant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointement>
     */
    public function getAppointements(): Collection
    {
        return $this->appointements;
    }

    public function addAppointement(Appointement $appointement): self
    {
        if (!$this->appointements->contains($appointement)) {
            $this->appointements->add($appointement);
            $appointement->setConsultant($this);
        }

        return $this;
    }

    public function removeAppointement(Appointement $appointement): self
    {
        if ($this->appointements->removeElement($appointement)) {
            // set the owning side to null (unless already changed)
            if ($appointement->getConsultant() === $this) {
                $appointement->setConsultant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecruitmentProcess>
     */
    public function getRecruitmentProcesses(): Collection
    {
        return $this->recruitmentProcesses;
    }

    public function addRecruitmentProcess(RecruitmentProcess $recruitmentProcess): self
    {
        if (!$this->recruitmentProcesses->contains($recruitmentProcess)) {
            $this->recruitmentProcesses->add($recruitmentProcess);
            $recruitmentProcess->setExternaticConsultant($this);
        }

        return $this;
    }

    public function removeRecruitmentProcess(RecruitmentProcess $recruitmentProcess): self
    {
        if ($this->recruitmentProcesses->removeElement($recruitmentProcess)) {
            // set the owning side to null (unless already changed)
            if ($recruitmentProcess->getExternaticConsultant() === $this) {
                $recruitmentProcess->setExternaticConsultant(null);
            }
        }

        return $this;
    }
}
