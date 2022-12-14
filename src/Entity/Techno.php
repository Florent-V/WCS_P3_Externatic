<?php

namespace App\Entity;

use App\Repository\TechnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnoRepository::class)]
class Techno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\ManyToMany(targetEntity: Annonce::class, mappedBy: 'techno')]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'techno', targetEntity: CandidatHasTechno::class)]
    private Collection $candidatHasTechnos;

    #[ORM\ManyToOne(inversedBy: 'technos')]
    private ?SearchProfile $searchProfile = null;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->candidatHasTechnos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

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
            $annonce->addTechno($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            $annonce->removeTechno($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CandidatHasTechno>
     */
    public function getCandidatHasTechnos(): Collection
    {
        return $this->candidatHasTechnos;
    }

    public function addCandidatHasTechno(CandidatHasTechno $candidatHasTechno): self
    {
        if (!$this->candidatHasTechnos->contains($candidatHasTechno)) {
            $this->candidatHasTechnos->add($candidatHasTechno);
            $candidatHasTechno->setTechno($this);
        }

        return $this;
    }

    public function removeCandidatHasTechno(CandidatHasTechno $candidatHasTechno): self
    {
        if ($this->candidatHasTechnos->removeElement($candidatHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($candidatHasTechno->getTechno() === $this) {
                $candidatHasTechno->setTechno(null);
            }
        }

        return $this;
    }

    public function getSearchProfile(): ?SearchProfile
    {
        return $this->searchProfile;
    }

    public function setSearchProfile(?SearchProfile $searchProfile): self
    {
        $this->searchProfile = $searchProfile;

        return $this;
    }
}
