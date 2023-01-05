<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'skills', targetEntity: SoftSkill::class)]
    private Collection $softSkill;

    #[ORM\OneToMany(mappedBy: 'skills', targetEntity: HardSkill::class)]
    private Collection $hardSkill;

    #[ORM\OneToOne(inversedBy: 'skills', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Curriculum $curriculum = null;

    #[ORM\OneToMany(mappedBy: 'skills', targetEntity: Language::class)]
    private Collection $languages;

    public function __construct()
    {
        $this->softSkill = new ArrayCollection();
        $this->hardSkill = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, SoftSkill>
     */
    public function getSoftSkill(): Collection
    {
        return $this->softSkill;
    }

    public function addSoftSkill(SoftSkill $softSkill): self
    {
        if (!$this->softSkill->contains($softSkill)) {
            $this->softSkill->add($softSkill);
            $softSkill->setSkills($this);
        }

        return $this;
    }

    public function removeSoftSkill(SoftSkill $softSkill): self
    {
        if ($this->softSkill->removeElement($softSkill)) {
            // set the owning side to null (unless already changed)
            if ($softSkill->getSkills() === $this) {
                $softSkill->setSkills(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HardSkill>
     */
    public function getHardSkill(): Collection
    {
        return $this->hardSkill;
    }

    public function addHardSkill(HardSkill $hardSkill): self
    {
        if (!$this->hardSkill->contains($hardSkill)) {
            $this->hardSkill->add($hardSkill);
            $hardSkill->setSkills($this);
        }

        return $this;
    }

    public function removeHardSkill(HardSkill $hardSkill): self
    {
        if ($this->hardSkill->removeElement($hardSkill)) {
            // set the owning side to null (unless already changed)
            if ($hardSkill->getSkills() === $this) {
                $hardSkill->setSkills(null);
            }
        }

        return $this;
    }

    public function getCurriculum(): ?Curriculum
    {
        return $this->curriculum;
    }

    public function setCurriculum(Curriculum $curriculum): self
    {
        // set the owning side of the relation if necessary
        if ($curriculum->getSkills() !== $this) {
            $curriculum->setSkills($this);
        }

        $this->curriculum = $curriculum;

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->setSkills($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getSkills() === $this) {
                $language->setSkills(null);
            }
        }

        return $this;
    }
}
