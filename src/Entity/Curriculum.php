<?php

namespace App\Entity;

use App\Repository\CurriculumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurriculumRepository::class)]
class Curriculum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'curriculum', targetEntity: Certification::class)]
    private Collection $certifications;

    #[ORM\OneToMany(mappedBy: 'curriculum', targetEntity: Experience::class, orphanRemoval: true)]
    private Collection $experience;

    #[ORM\OneToMany(mappedBy: 'curriculum', targetEntity: Hobbie::class)]
    private Collection $hobbie;

    #[ORM\OneToOne(inversedBy: 'curriculum', cascade: ['persist', 'remove'])]
    private ?Candidat $candidat = null;

    #[ORM\OneToOne(inversedBy: 'curriculum', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Skills $skills = null;

    #[ORM\OneToMany(mappedBy: 'curriculum', targetEntity: CurriculumHasTechno::class, orphanRemoval: true)]
    private Collection $curriculumHasTechnos;

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
        $this->experience = new ArrayCollection();
        $this->hobbie = new ArrayCollection();
        $this->curriculumHasTechnos = new ArrayCollection();
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
            $certification->setCurriculum($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getCurriculum() === $this) {
                $certification->setCurriculum(null);
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
            $experience->setCurriculum($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experience->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCurriculum() === $this) {
                $experience->setCurriculum(null);
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
            $hobbie->setCurriculum($this);
        }

        return $this;
    }

    public function removeHobbie(Hobbie $hobbie): self
    {
        if ($this->hobbie->removeElement($hobbie)) {
            // set the owning side to null (unless already changed)
            if ($hobbie->getCurriculum() === $this) {
                $hobbie->setCurriculum(null);
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
     * @return Collection<int, CurriculumHasTechno>
     */
    public function getCurriculumHasTechnos(): Collection
    {
        return $this->curriculumHasTechnos;
    }

    public function addCurriculumHasTechno(CurriculumHasTechno $curriculumHasTechno): self
    {
        if (!$this->curriculumHasTechnos->contains($curriculumHasTechno)) {
            $this->curriculumHasTechnos->add($curriculumHasTechno);
            $curriculumHasTechno->setCurriculum($this);
        }

        return $this;
    }

    public function removeCurriculumHasTechno(CurriculumHasTechno $curriculumHasTechno): self
    {
        if ($this->curriculumHasTechnos->removeElement($curriculumHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($curriculumHasTechno->getCurriculum() === $this) {
                $curriculumHasTechno->setCurriculum(null);
            }
        }

        return $this;
    }
}
