<?php

namespace App\Entity;

use App\Repository\SearchProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchProfileRepository::class)]
class SearchProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'searchProfiles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Candidat $candidat = null;

    #[ORM\Column]
    private ?array $searchQuery = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSearchQuery(): ?array
    {
        return $this->searchQuery;
    }

    public function setSearchQuery(array $searchQuery): self
    {
        $this->searchQuery = $searchQuery;

        return $this;
    }
}
