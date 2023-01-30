<?php

namespace App\Entity;

use App\Repository\NotifRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotifRepository::class)]
class Notif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?bool $wasRead = false;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?bool $inSummary = true;

    #[ORM\ManyToOne(inversedBy: 'notifs')]
    private ?Annonce $annonce = null;

    #[ORM\OneToOne(inversedBy: 'notif', cascade: ['persist', 'remove'])]
    private ?Message $message = null;

    #[ORM\Column]
    private ?bool $active = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isWasRead(): ?bool
    {
        return $this->wasRead;
    }

    public function setWasRead(bool $wasRead): self
    {
        $this->wasRead = $wasRead;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isInSummary(): ?bool
    {
        return $this->inSummary;
    }

    public function setInSummary(bool $isInSummary): self
    {
        $this->inSummary = $isInSummary;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $isActive): self
    {
        $this->active = $isActive;

        return $this;
    }
}
