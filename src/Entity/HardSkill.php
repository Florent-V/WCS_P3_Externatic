<?php

namespace App\Entity;

use App\Repository\HardSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HardSkillRepository::class)]
class HardSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez donner un nom à votre compétence')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'hardSkill')]
    private ?Skills $skills = null;

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
