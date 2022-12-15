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

    #[ORM\ManyToOne(inversedBy: 'technos')]
    private ?SearchProfile $searchProfile = null;

    #[ORM\OneToMany(mappedBy: 'techno', targetEntity: CvHasTechno::class)]
    private Collection $cvHasTechnos;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->cvHasTechnos = new ArrayCollection();
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

    public function getSearchProfile(): ?SearchProfile
    {
        return $this->searchProfile;
    }

    public function setSearchProfile(?SearchProfile $searchProfile): self
    {
        $this->searchProfile = $searchProfile;

        return $this;
    }

    /**
     * @return Collection<int, CvHasTechno>
     */
    public function getCvHasTechnos(): Collection
    {
        return $this->cvHasTechnos;
    }

    public function addCvHasTechno(CvHasTechno $cvHasTechno): self
    {
        if (!$this->cvHasTechnos->contains($cvHasTechno)) {
            $this->cvHasTechnos->add($cvHasTechno);
            $cvHasTechno->setTechno($this);
        }

        return $this;
    }

    public function removeCvHasTechno(CvHasTechno $cvHasTechno): self
    {
        if ($this->cvHasTechnos->removeElement($cvHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($cvHasTechno->getTechno() === $this) {
                $cvHasTechno->setTechno(null);
            }
        }

        return $this;
    }
}
