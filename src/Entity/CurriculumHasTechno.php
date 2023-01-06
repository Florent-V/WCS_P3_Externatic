<?php

namespace App\Entity;

use App\Repository\CurriculumHasTechnoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurriculumHasTechnoRepository::class)]
class CurriculumHasTechno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'curriculumHasTechnos')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Curriculum $curriculum = null;

    #[ORM\ManyToOne(inversedBy: 'curriculumHasTechnos')]
    private ?Techno $techno = null;

    #[ORM\Column(length: 100)]
    private ?string $level = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurriculum(): ?Curriculum
    {
        return $this->curriculum;
    }

    public function setCurriculum(?Curriculum $curriculum): self
    {
        $this->curriculum = $curriculum;

        return $this;
    }

    public function getTechno(): ?Techno
    {
        return $this->techno;
    }

    public function setTechno(?Techno $techno): self
    {
        $this->techno = $techno;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }
}
