<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 80)]
    private ?string $firstname = null;

    #[ORM\Column(length: 80)]
    private ?string $lastName = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?ExternaticConsultant $consultant = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Candidat $candidat = null;

    #[ORM\OneToMany(mappedBy: 'sendTo', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $receivedMessages;

    #[ORM\OneToMany(mappedBy: 'sendBy', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $sentMessages;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(length: 45)]
    private ?string $phone = null;
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notif::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\Column]
    private ?bool $isActive = true;
    #[ORM\Column]
    private ?bool $hasNotifUnread = false;

    public function __construct()
    {
        $this->receivedMessages = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getConsultant(): ?ExternaticConsultant
    {
        return $this->consultant;
    }

    public function setConsultant(ExternaticConsultant $consultant): self
    {
        // set the owning side of the relation if necessary
        if ($consultant->getUser() !== $this) {
            $consultant->setUser($this);
        }

        $this->consultant = $consultant;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        // unset the owning side of the relation if necessary
        if ($candidat === null && $this->candidat !== null) {
            $this->candidat->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($candidat !== null && $candidat->getUser() !== $this) {
            $candidat->setUser($this);
        }

        $this->candidat = $candidat;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->add($receivedMessage);
            $receivedMessage->setSendTo($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessages->removeElement($receivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getSendTo() === $this) {
                $receivedMessage->setSendTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->add($sentMessage);
            $sentMessage->setSendBy($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSendBy() === $this) {
                $sentMessage->setSendBy(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Notif>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notif $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notif $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
// set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isHasNotifUnread(): ?bool
    {
        return $this->hasNotifUnread;
    }

    public function setHasNotifUnread(bool $hasNotifUnread): self
    {
        $this->hasNotifUnread = $hasNotifUnread;
        return $this;
    }
}
