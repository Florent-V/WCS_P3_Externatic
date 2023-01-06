<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 45)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $contactName = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $information = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ExternaticConsultant $externaticConsultant = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Annonce::class, orphanRemoval: true)]
    private Collection $annonces;

    #[ORM\ManyToMany(targetEntity: Candidat::class, mappedBy: 'favoriteCompanies')]
    private Collection $followers;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->followers = new ArrayCollection();
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
}
