<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez donner un nom à votre certification')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le titre {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une date')]
    #[Assert\LessThanOrEqual('today')]
    private ?\DateTime $beginning = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une date')]
    #[Assert\LessThanOrEqual('today')]
    #[Assert\Expression(
        "this.getBeginning() < this.getEnd()",
        message : "La date de fin doit être postérieure à la date de début"
    )]
    private ?\DateTime $end = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre saisie : {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $organism = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre saisie : {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 500,
        maxMessage: 'Attention, {{ limit }} caractères maximum.'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre saisie : {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $diploma = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isFormation = null;

    #[ORM\ManyToOne(inversedBy: 'experience')]
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

    public function getBeginning(): ?\DateTime
    {
        return $this->beginning;
    }

    public function setBeginning(?\DateTime $beginning): self
    {
        $this->beginning = $beginning;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(?\DateTime $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getOrganism(): ?string
    {
        return $this->organism;
    }

    public function setOrganism(?string $organism): self
    {
        $this->organism = $organism;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDiploma(): ?string
    {
        return $this->diploma;
    }

    public function setDiploma(?string $diploma): self
    {
        $this->diploma = $diploma;

        return $this;
    }

    public function isIsFormation(): ?bool
    {
        return $this->isFormation;
    }

    public function setIsFormation(?bool $isFormation): self
    {
        $this->isFormation = $isFormation;

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
