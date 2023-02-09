<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?int $salaryMin = null;

    #[ORM\Column(length: 45)]
    private ?string $contractType = null;

    #[ORM\Column(length: 45)]
    private ?string $studyLevel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $remote = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Vous devez mettre une description à votre annonce')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $publicationStatus = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endingAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExternaticConsultant $author = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: RecruitmentProcess::class, orphanRemoval: true)]
    private Collection $recrutementProcesses;

    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'annonces', cascade:['persist'])]
    private Collection $techno;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $optionalInfo = null;

    #[ORM\Column(nullable: true)]
    private ?int $salaryMax = null;

    #[ORM\ManyToMany(targetEntity: Candidat::class, mappedBy: 'favoriteOffers')]
    private Collection $followers;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $contractDuration = null;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $workTime = null;
    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Notif::class)]
    private Collection $notifs;

    public function __construct()
    {
        $this->recrutementProcesses = new ArrayCollection();
        $this->techno = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->notifs = new ArrayCollection();
    }

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

    public function getSalaryMin(): ?int
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(int $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getStudyLevel(): ?string
    {
        return $this->studyLevel;
    }

    public function setStudyLevel(string $studyLevel): self
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }

    public function isRemote(): ?bool
    {
        return $this->remote;
    }

    public function setRemote(?bool $remote): self
    {
        $this->remote = $remote;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationStatus(): ?int
    {
        return $this->publicationStatus;
    }

    public function setPublicationStatus(?int $publicationStatus): self
    {
        $this->publicationStatus = $publicationStatus;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeInterface
    {
        return $this->endingAt;
    }

    public function setEndingAt(?\DateTimeInterface $endingAt): self
    {
        $this->endingAt = $endingAt;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAuthor(): ?ExternaticConsultant
    {
        return $this->author;
    }

    public function setAuthor(?ExternaticConsultant $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, RecruitmentProcess>
     */
    public function getRecrutementProcesses(): Collection
    {
        return $this->recrutementProcesses;
    }

    public function addRecrutementProcess(RecruitmentProcess $recrutementProcess): self
    {
        if (!$this->recrutementProcesses->contains($recrutementProcess)) {
            $this->recrutementProcesses->add($recrutementProcess);
            $recrutementProcess->setAnnonce($this);
        }

        return $this;
    }

    public function removeRecrutementProcess(RecruitmentProcess $recrutementProcess): self
    {
        if ($this->recrutementProcesses->removeElement($recrutementProcess)) {
            // set the owning side to null (unless already changed)
            if ($recrutementProcess->getAnnonce() === $this) {
                $recrutementProcess->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Techno>
     */
    public function getTechno(): Collection
    {
        return $this->techno;
    }

    public function addTechno(Techno $techno): self
    {
        if (!$this->techno->contains($techno)) {
            $this->techno->add($techno);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): self
    {
        $this->techno->removeElement($techno);

        return $this;
    }

    public function getOptionalInfo(): ?string
    {
        return $this->optionalInfo;
    }

    public function setOptionalInfo(?string $optionalInfo): self
    {
        $this->optionalInfo = $optionalInfo;

        return $this;
    }

    public function getSalaryMax(): ?int
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?int $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    /**
     * @return Collection<int, Candidat>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Candidat $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->addToFavoriteOffer($this);
        }

        return $this;
    }

    public function removeFollower(Candidat $follower): self
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeFromFavoriteOffer($this);
        }

        return $this;
    }

    public function getContractDuration(): ?\DateInterval
    {
        return $this->contractDuration;
    }

    public function setContractDuration(?\DateInterval $contractDuration): self
    {
        $this->contractDuration = $contractDuration;

        return $this;
    }

    public function getWorkTime(): ?\DateInterval
    {
        return $this->workTime;
    }

    public function setWorkTime(?\DateInterval $workTime): self
    {
        $this->workTime = $workTime;

        return $this;
    }

    /**
     * @return Collection<int, Notif>
     */
    public function getNotifs(): Collection
    {
        return $this->notifs;
    }

    public function addNotif(Notif $notif): self
    {
        if (!$this->notifs->contains($notif)) {
            $this->notifs->add($notif);
            $notif->setAnnonce($this);
        }

        return $this;
    }

    public function removeNotif(Notif $notif): self
    {
        if ($this->notifs->removeElement($notif)) {
// set the owning side to null (unless already changed)
            if ($notif->getAnnonce() === $this) {
                $notif->setAnnonce(null);
            }
        }

        return $this;
    }
}
