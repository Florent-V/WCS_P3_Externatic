<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CvRepository::class)]
class Cv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Certification::class)]
    private Collection $certifications;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Experience::class)]
    private Collection $experience;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Language::class)]
    private Collection $language;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Hobbie::class)]
    private Collection $hobbie;

    #[ORM\OneToOne(inversedBy: 'cv', cascade: ['persist', 'remove'])]
    private ?Candidat $candidat = null;

    #[ORM\OneToOne(inversedBy: 'cv', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Skills $skills = null;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: CvHasTechno::class, orphanRemoval: true)]
    private Collection $cvHasTechnos;

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
        $this->experience = new ArrayCollection();
        $this->language = new ArrayCollection();
        $this->hobbie = new ArrayCollection();
        $this->cvHasTechnos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setCv($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getCv() === $this) {
                $certification->setCv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experience->contains($experience)) {
            $this->experience->add($experience);
            $experience->setCv($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experience->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCv() === $this) {
                $experience->setCv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language->add($language);
            $language->setCv($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->language->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getCv() === $this) {
                $language->setCv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hobbie>
     */
    public function getHobbie(): Collection
    {
        return $this->hobbie;
    }

    public function addHobbie(Hobbie $hobbie): self
    {
        if (!$this->hobbie->contains($hobbie)) {
            $this->hobbie->add($hobbie);
            $hobbie->setCv($this);
        }

        return $this;
    }

    public function removeHobbie(Hobbie $hobbie): self
    {
        if ($this->hobbie->removeElement($hobbie)) {
            // set the owning side to null (unless already changed)
            if ($hobbie->getCv() === $this) {
                $hobbie->setCv(null);
            }
        }

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

    public function getSkills(): ?Skills
    {
        return $this->skills;
    }

    public function setSkills(Skills $skills): self
    {
        $this->skills = $skills;

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
            $cvHasTechno->setCv($this);
        }

        return $this;
    }

    public function removeCvHasTechno(CvHasTechno $cvHasTechno): self
    {
        if ($this->cvHasTechnos->removeElement($cvHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($cvHasTechno->getCv() === $this) {
                $cvHasTechno->setCv(null);
            }
        }

        return $this;
    }
}
