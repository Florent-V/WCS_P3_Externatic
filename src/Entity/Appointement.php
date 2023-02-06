<?php

namespace App\Entity;

use App\Repository\AppointementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointementRepository::class)]
class Appointement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'appointements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecruitmentProcess $recruitmentProcess = null;

    #[ORM\ManyToOne(inversedBy: 'appointements')]
    private ?ExternaticConsultant $consultant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateInterval $length = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adress = null;

    #[ORM\OneToMany(mappedBy: 'appointment', targetEntity: Notif::class)]
    private Collection $notifs;

    public function __construct()
    {
        $this->notifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecruitmentProcess(): ?RecruitmentProcess
    {
        return $this->recruitmentProcess;
    }

    public function setRecruitmentProcess(?RecruitmentProcess $recruitmentProcess): self
    {
        $this->recruitmentProcess = $recruitmentProcess;

        return $this;
    }

    public function getConsultant(): ?ExternaticConsultant
    {
        return $this->consultant;
    }

    public function setConsultant(?ExternaticConsultant $consultant): self
    {
        $this->consultant = $consultant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getLength(): ?\DateInterval
    {
        return $this->length;
    }

    public function setLength(\DateInterval $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

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
            $notif->setAppointment($this);
        }

        return $this;
    }

    public function removeNotif(Notif $notif): self
    {
        if ($this->notifs->removeElement($notif)) {
            // set the owning side to null (unless already changed)
            if ($notif->getAppointment() === $this) {
                $notif->setAppointment(null);
            }
        }

        return $this;
    }
}
