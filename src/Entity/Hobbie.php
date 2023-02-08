<?php

namespace App\Entity;

use App\Repository\HobbieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HobbieRepository::class)]
class Hobbie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez donner un nom à votre hobby')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'hobbie')]
    private ?Curriculum $curriculum = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
}
