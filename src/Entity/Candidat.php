<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedIn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $canPostulate = false;

    #[ORM\OneToOne(inversedBy: 'candidat', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: RecruitmentProcess::class, orphanRemoval: true)]
    private Collection $recrutementProcesses;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: SearchProfile::class, orphanRemoval: true)]
    private Collection $searchProfiles;

    #[ORM\OneToOne(mappedBy: 'candidat', cascade: ['persist', 'remove'])]
    private ?Curriculum $curriculum = null;

    #[ORM\ManyToMany(targetEntity: Annonce::class, inversedBy: 'followers')]
    #[ORM\JoinTable(name:'favorite_offers')]
    private Collection $favoriteOffers;

    public function __construct()
    {
        $this->recrutementProcesses = new ArrayCollection();
        $this->searchProfiles = new ArrayCollection();
        $this->favoriteOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLinkedIn(): ?string
    {
        return $this->linkedIn;
    }

    public function setLinkedIn(?string $linkedIn): self
    {
        $this->linkedIn = $linkedIn;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isCanPostulate(): ?bool
    {
        return $this->canPostulate;
    }

    public function setCanPostulate(bool $canPostulate): self
    {
        $this->canPostulate = $canPostulate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, RecruitmentProcess>
     */
    public function getRecrutementProcesses(): Collection
    {
        return $this->recrutementProcesses;
    }
    public function addRecrutementProcess(RecruitmentProcess $recrutementProcess): self
    {
        if (!$this->recrutementProcesses->contains($recrutementProcess)) {
            $this->recrutementProcesses->add($recrutementProcess);
            $recrutementProcess->setCandidat($this);
        }
        return $this;
    }
    public function removeRecrutementProcess(RecruitmentProcess $recrutementProcess): self
    {
        if ($this->recrutementProcesses->removeElement($recrutementProcess)) {
            // set the owning side to null (unless already changed)
            if ($recrutementProcess->getCandidat() === $this) {
                $recrutementProcess->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SearchProfile>
     */
    public function getSearchProfiles(): Collection
    {
        return $this->searchProfiles;
    }

    public function addSearchProfile(SearchProfile $searchProfile): self
    {
        if (!$this->searchProfiles->contains($searchProfile)) {
            $this->searchProfiles->add($searchProfile);
            $searchProfile->setCandidat($this);
        }

        return $this;
    }

    public function removeSearchProfile(SearchProfile $searchProfile): self
    {
        if ($this->searchProfiles->removeElement($searchProfile)) {
            // set the owning side to null (unless already changed)
            if ($searchProfile->getCandidat() === $this) {
                $searchProfile->setCandidat(null);
            }
        }

        return $this;
    }

    public function getCurriculum(): ?Curriculum
    {
        return $this->curriculum;
    }

    public function setCurriculum(?Curriculum $curriculum): self
    {
        // unset the owning side of the relation if necessary
        if ($curriculum === null && $this->curriculum !== null) {
            $this->curriculum->setCandidat(null);
        }

        // set the owning side of the relation if necessary
        if ($curriculum !== null && $curriculum->getCandidat() !== $this) {
            $curriculum->setCandidat($this);
        }

        $this->curriculum = $curriculum;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getFavoriteOffers(): Collection
    {
        return $this->favoriteOffers;
    }

    public function addToFavoriteOffer(Annonce $favoriteOffer): self
    {
        if (!$this->favoriteOffers->contains($favoriteOffer)) {
            $this->favoriteOffers->add($favoriteOffer);
        }

        return $this;
    }

    public function removeFromFavoriteOffer(Annonce $favoriteOffer): self
    {
        $this->favoriteOffers->removeElement($favoriteOffer);

        return $this;
    }

    public function isInFavorite(Annonce $annonce): bool
    {
        return $this->favoriteOffers->contains($annonce);
    }
}
