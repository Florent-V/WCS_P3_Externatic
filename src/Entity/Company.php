<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Vich\Uploadable]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une numéro de Siret')]
    #[Assert\Regex(
        pattern: '/\d{14}/',
        message: '{{ value }} n\'est pas un siret valide'
    )]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une nom')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = 'companyLogoIpsum.svg';

    #[Vich\UploadableField(mapping: 'logo_picture', fileNameProperty: 'logo')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $logoFile = null;

    /**
     * @return File|null
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File|null $logoFile
     * @return Company
     */
    public function setLogoFile(?File $logoFile): Company
    {
        $this->logoFile = $logoFile;
        if ($logoFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: 'Vous devez indiquer un code postal')]
    #[Assert\Regex(
        pattern: '/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/',
        message: '{{ value }} n\'est pas un code postal valide'
    )]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une adresse')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'adresse saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères'
    )]
    private ?string $address = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez indiquer une ville')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'La ville saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
    )]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Vous devez indiquer un numéro de téléphone')]
    #[Assert\Length(
        max: 15,
        maxMessage: 'Ce numéro semble bien trop long, veuillez ne pas dépasser {{ limit }} caractères',
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Vous devez indiquer le nom du contact')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le nom du contact ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $contactName = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(
        type: 'integer',
        message: '{{ value }} n\'est pas un nombre entier.',
    )]
    private ?int $size = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type(
        type: 'string',
        message: 'Ces informations doivent être une chaîne de caractère',
    )]
    private ?string $information = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ExternaticConsultant $externaticConsultant = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Annonce::class, orphanRemoval: true)]
    private Collection $annonces;

    #[ORM\ManyToMany(targetEntity: Candidat::class, mappedBy: 'favoriteCompanies')]
    private Collection $followers;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: RecruitmentProcess::class)]
    private Collection $recruitmentProcesses;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->recruitmentProcesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): self
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

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

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setCompany($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getCompany() === $this) {
                $annonce->setCompany(null);
            }
        }

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
            $follower->addCompanyToFavorite($this);
        }

        return $this;
    }

    public function removeFollower(Candidat $follower): self
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeCompanyFromFavorite($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, RecruitmentProcess>
     */
    public function getRecruitmentProcesses(): Collection
    {
        return $this->recruitmentProcesses;
    }

    public function addRecruitmentProcess(RecruitmentProcess $recruitmentProcess): self
    {
        if (!$this->recruitmentProcesses->contains($recruitmentProcess)) {
            $this->recruitmentProcesses->add($recruitmentProcess);
            $recruitmentProcess->setCompany($this);
        }

        return $this;
    }

    public function removeRecruitmentProcess(RecruitmentProcess $recruitmentProcess): self
    {
        if ($this->recruitmentProcesses->removeElement($recruitmentProcess)) {
            // set the owning side to null (unless already changed)
            if ($recruitmentProcess->getCompany() === $this) {
                $recruitmentProcess->setCompany(null);
            }
        }

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
