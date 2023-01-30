<?php

namespace App\Entity;

use App\Repository\TechnoRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TechnoRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Techno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: 'techno_picture', fileNameProperty: 'picture')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $pictureFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Annonce::class, mappedBy: 'techno')]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'techno', targetEntity: CurriculumHasTechno::class)]
    private Collection $curriculumHasTechnos;

    #[ORM\ManyToMany(targetEntity: SearchProfile::class, mappedBy: 'techno')]
    private Collection $searchProfiles;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->curriculumHasTechnos = new ArrayCollection();
        $this->searchProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

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
            $annonce->addTechno($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            $annonce->removeTechno($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CurriculumHasTechno>
     */
    public function getCurriculumHasTechnos(): Collection
    {
        return $this->curriculumHasTechnos;
    }
    public function addCurriculumHasTechno(CurriculumHasTechno $curriculumHasTechno): self
    {
        if (!$this->curriculumHasTechnos->contains($curriculumHasTechno)) {
            $this->curriculumHasTechnos->add($curriculumHasTechno);
            $curriculumHasTechno->setTechno($this);
        }
        return $this;
    }
    public function removeCurriculumHasTechno(CurriculumHasTechno $curriculumHasTechno): self
    {
        if ($this->curriculumHasTechnos->removeElement($curriculumHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($curriculumHasTechno->getTechno() === $this) {
                $curriculumHasTechno->setTechno(null);
            }
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * @param File|null $pictureFile
     */
    public function setPictureFile(?File $pictureFile = null): self
    {
        $this->pictureFile = $pictureFile;
        if ($pictureFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return DatetimeInterface|null
     */
    public function getUpdatedAt(): ?DatetimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DatetimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?DatetimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return Collection<int, SearchProfile>
     */
    public function getSearchProfiles(): Collection
    {
        return $this->searchProfiles;
    }

    public function addSearchProfile(SearchProfile $searchProfile): self
    {
        if (!$this->searchProfiles->contains($searchProfile)) {
            $this->searchProfiles->add($searchProfile);
            $searchProfile->addTechno($this);
        }

        return $this;
    }

    public function removeSearchProfile(SearchProfile $searchProfile): self
    {
        if ($this->searchProfiles->removeElement($searchProfile)) {
            $searchProfile->removeTechno($this);
        }

        return $this;
    }
}
