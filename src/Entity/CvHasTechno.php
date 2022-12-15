<?php

namespace App\Entity;

use App\Repository\CvHasTechnoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CvHasTechnoRepository::class)]
class CvHasTechno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cvHasTechnos')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Cv $cv = null;

    #[ORM\ManyToOne(inversedBy: 'cvHasTechnos')]
    private ?Techno $techno = null;

    #[ORM\Column(length: 100)]
    private ?string $level = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

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
