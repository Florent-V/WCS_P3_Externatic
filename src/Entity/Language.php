<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: 'Vous devez donner un nom à votre langue')]
    #[Assert\Length(
        max: 45,
        maxMessage: 'Le nom {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $language = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $level = null;

    #[ORM\ManyToOne(inversedBy: 'languages')]
    private ?Skills $skills = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSkills(): ?Skills
    {
        return $this->skills;
    }

    public function setSkills(?Skills $skills): self
    {
        $this->skills = $skills;

        return $this;
    }
}
