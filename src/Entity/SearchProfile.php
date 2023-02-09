<?php

namespace App\Entity;

use App\Repository\SearchProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchProfileRepository::class)]
class SearchProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'searchProfiles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Candidat $candidat = null;

    #[ORM\Column]
    private ?array $searchQuery = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?bool $workTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $period = null;

    #[ORM\Column(nullable: true)]
    private ?int $companyId = null;

    #[ORM\Column(nullable: true)]
    private ?int $salaryMin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $remote = null;

    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'searchProfiles')]
    private Collection $techno;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $contractType = null;

    public function __construct()
    {
        $this->techno = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getSearchQuery(): ?array
    {
        return $this->searchQuery;
    }

    public function setSearchQuery(array $searchQuery): self
    {
        $this->searchQuery = $searchQuery;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isWorkTime(): ?bool
    {
        return $this->workTime;
    }

    public function setWorkTime(?bool $workTime): self
    {
        $this->workTime = $workTime;

        return $this;
    }

    public function isPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getSalaryMin(): ?int
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?int $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function isRemote(): ?bool
    {
        return $this->remote;
    }

    public function setRemote(?bool $remote): self
    {
        $this->remote = $remote;

        return $this;
    }

    /**
     * @return Collection<int, Techno>
     */
    public function getTechno(): Collection
    {
        return $this->techno;
    }

    public function addTechno(Techno $techno): self
    {
        if (!$this->techno->contains($techno)) {
            $this->techno->add($techno);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): self
    {
        $this->techno->removeElement($techno);

        return $this;
    }

    public function getContractType(): ?array
    {
        return $this->contractType;
    }

    public function setContractType(?array $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }
}
