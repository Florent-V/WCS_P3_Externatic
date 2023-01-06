<?php

namespace App\Entity;

use App\Repository\AppointementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointementRepository::class)]
class Appointement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'appointements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecruitmentProcess $recruitmentProcess = null;

    #[ORM\ManyToOne(inversedBy: 'appointements')]
    private ?ExternaticConsultant $consultant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

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
}
