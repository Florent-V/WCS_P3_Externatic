<?php

namespace App\Entity;

use App\Repository\RecruitmentProcessRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruitmentProcessRepository::class)]
#[ORM\HasLifecycleCallbacks]
class RecruitmentProcess
{
    public const RECRUIT_STATUS = [
        "Applied",
        "In progress",
        "Completed",
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $information = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'recrutementProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'recrutementProcesses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Annonce $annonce = null;

    #[ORM\OneToMany(mappedBy: 'recruitmentProcess', targetEntity: Appointement::class, orphanRemoval: true)]
    private Collection $appointements;

    #[ORM\OneToMany(mappedBy: 'recruitmentProcess', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'recruitmentProcesses')]
    private ?Company $company = null;

    #[ORM\Column]
    private ?bool $readByConsultant = false;

    #[ORM\Column]
    private ?bool $readByCandidat = false;

    #[ORM\Column]
    private ?bool $archivedByCandidat = false;

    #[ORM\Column]
    private ?bool $archivedByConsultant = false;

    #[ORM\ManyToOne(inversedBy: 'recruitmentProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExternaticConsultant $externaticConsultant = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new Datetime();
    }

    public function __construct()
    {
        $this->appointements = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

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

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

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

    /**
     * @return Collection<int, Appointement>
     */
    public function getAppointements(): Collection
    {
        return $this->appointements;
    }

    public function addAppointement(Appointement $appointement): self
    {
        if (!$this->appointements->contains($appointement)) {
            $this->appointements->add($appointement);
            $appointement->setRecruitmentProcess($this);
        }

        return $this;
    }

    public function removeAppointement(Appointement $appointement): self
    {
        if ($this->appointements->removeElement($appointement)) {
            // set the owning side to null (unless already changed)
            if ($appointement->getRecruitmentProcess() === $this) {
                $appointement->setRecruitmentProcess(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setRecruitmentProcess($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRecruitmentProcess() === $this) {
                $message->setRecruitmentProcess(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function isReadByConsultant(): ?bool
    {
        return $this->readByConsultant;
    }

    public function setReadByConsultant(bool $readByConsultant): self
    {
        $this->readByConsultant = $readByConsultant;

        return $this;
    }

    public function isReadByCandidat(): ?bool
    {
        return $this->readByCandidat;
    }

    public function setReadByCandidat(bool $readByCandidat): self
    {
        $this->readByCandidat = $readByCandidat;

        return $this;
    }

    public function isArchivedByCandidat(): ?bool
    {
        return $this->archivedByCandidat;
    }

    public function setArchivedByCandidat(bool $archivedByCandidat): self
    {
        $this->archivedByCandidat = $archivedByCandidat;

        return $this;
    }

    public function isArchivedByConsultant(): ?bool
    {
        return $this->archivedByConsultant;
    }

    public function setArchivedByConsultant(bool $archivedByConsultant): self
    {
        $this->archivedByConsultant = $archivedByConsultant;

        return $this;
    }

    public function getExternaticConsultant(): ?ExternaticConsultant
    {
        return $this->externaticConsultant;
    }

    public function setExternaticConsultant(?ExternaticConsultant $externaticConsultant): self
    {
        $this->externaticConsultant = $externaticConsultant;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
