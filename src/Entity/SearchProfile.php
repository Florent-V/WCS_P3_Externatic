<?php

namespace App\Entity;

use App\Repository\SearchProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchProfileRepository::class)]
class SearchProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $salaryMax = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $salaryMin = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $workTime = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRemote = null;

    #[ORM\ManyToOne(inversedBy: 'searchProfiles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Candidat $candidat = null;

    #[ORM\OneToMany(mappedBy: 'searchProfile', targetEntity: Techno::class)]
    private Collection $technos;

    public function __construct()
    {
        $this->technos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalaryMax(): ?string
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?string $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    public function getSalaryMin(): ?string
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?string $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function getWorkTime(): ?string
    {
        return $this->workTime;
    }

    public function setWorkTime(?string $workTime): self
    {
        $this->workTime = $workTime;

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

    public function isIsRemote(): ?bool
    {
        return $this->isRemote;
    }

    public function setIsRemote(?bool $isRemote): self
    {
        $this->isRemote = $isRemote;

        return $this;
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

    /**
     * @return Collection<int, Techno>
     */
    public function getTechnos(): Collection
    {
        return $this->technos;
    }

    public function addTechno(Techno $techno): self
    {
        if (!$this->technos->contains($techno)) {
            $this->technos->add($techno);
            $techno->setSearchProfile($this);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): self
    {
        if ($this->technos->removeElement($techno)) {
            // set the owning side to null (unless already changed)
            if ($techno->getSearchProfile() === $this) {
                $techno->setSearchProfile(null);
            }
        }

        return $this;
    }
}
