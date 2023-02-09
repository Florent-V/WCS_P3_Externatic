<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez donner un nom à votre certification')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une date')]
    #[Assert\LessThanOrEqual('today')]
    private ?\DateTime $year = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Vous devez indiquer un lien')]
    #[Assert\Url(['message' => 'Vous devez entrer un lien valide'])]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le lien {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères.'
    )]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'certifications')]
    private ?Curriculum $curriculum = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Vous devez entrer une description')]
    #[Assert\Length(
        max: 500,
        maxMessage: 'Attention, {{ limit }} caractères maximum.'
    )]
    private ?string $description = null;

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

    public function getYear(): ?\DateTime
    {
        return $this->year;
    }

    public function setYear(?\DateTime $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
