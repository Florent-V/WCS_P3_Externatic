<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedIn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvFile = null;

    #[ORM\Column]
    private ?bool $canPostulate = null;

    #[ORM\OneToOne(inversedBy: 'candidat', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Experience::class, orphanRemoval: true)]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Certification::class, orphanRemoval: true)]
    private Collection $certifications;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Language::class, orphanRemoval: true)]
    private Collection $languages;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: SoftSkill::class, orphanRemoval: true)]
    private Collection $softSkills;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: HardSkill::class, orphanRemoval: true)]
    private Collection $hardSkills;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Hobbie::class, orphanRemoval: true)]
    private Collection $hobbies;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: RecrutementProcess::class, orphanRemoval: true)]
    private Collection $recrutementProcesses;

    #[ORM\ManyToMany(targetEntity: Annonce::class, mappedBy: 'favorite')]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: SearchProfile::class, orphanRemoval: true)]
    private Collection $searchProfiles;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: CandidatHasTechno::class)]
    private Collection $candidatHasTechnos;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->softSkills = new ArrayCollection();
        $this->hardSkills = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
        $this->recrutementProcesses = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->searchProfiles = new ArrayCollection();
        $this->candidatHasTechnos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLinkedIn(): ?string
    {
        return $this->linkedIn;
    }

    public function setLinkedIn(?string $linkedIn): self
    {
        $this->linkedIn = $linkedIn;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCvFile(): ?string
    {
        return $this->cvFile;
    }

    public function setCvFile(?string $cvFile): self
    {
        $this->cvFile = $cvFile;

        return $this;
    }

    public function isCanPostulate(): ?bool
    {
        return $this->canPostulate;
    }

    public function setCanPostulate(bool $canPostulate): self
    {
        $this->canPostulate = $canPostulate;

        return $this;
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

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setCandidat($this);
        }
        return $this;
    }
    /**
     * @return Collection<int, RecrutementProcess>
     */
    public function getRecrutementProcesses(): Collection
    {
        return $this->recrutementProcesses;
    }

    public function addRecrutementProcess(RecrutementProcess $recrutementProcess): self
    {
        if (!$this->recrutementProcesses->contains($recrutementProcess)) {
            $this->recrutementProcesses->add($recrutementProcess);
            $recrutementProcess->setCandidat($this);
        }
        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCandidat() === $this) {
                $experience->setCandidat(null);
            }
        }
        return $this;
    }
    public function removeRecrutementProcess(RecrutementProcess $recrutementProcess): self
    {
        if ($this->recrutementProcesses->removeElement($recrutementProcess)) {
            // set the owning side to null (unless already changed)
            if ($recrutementProcess->getCandidat() === $this) {
                $recrutementProcess->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setCandidat($this);
        }
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
            $annonce->addFavorite($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getCandidat() === $this) {
                $certification->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->setCandidat($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getCandidat() === $this) {
                $language->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SoftSkill>
     */
    public function getSoftSkills(): Collection
    {
        return $this->softSkills;
    }

    public function addSoftSkill(SoftSkill $softSkill): self
    {
        if (!$this->softSkills->contains($softSkill)) {
            $this->softSkills->add($softSkill);
            $softSkill->setCandidat($this);
        }

        return $this;
    }

    public function removeSoftSkill(SoftSkill $softSkill): self
    {
        if ($this->softSkills->removeElement($softSkill)) {
            // set the owning side to null (unless already changed)
            if ($softSkill->getCandidat() === $this) {
                $softSkill->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HardSkill>
     */
    public function getHardSkills(): Collection
    {
        return $this->hardSkills;
    }

    public function addHardSkill(HardSkill $hardSkill): self
    {
        if (!$this->hardSkills->contains($hardSkill)) {
            $this->hardSkills->add($hardSkill);
            $hardSkill->setCandidat($this);
        }

        return $this;
    }

    public function removeHardSkill(HardSkill $hardSkill): self
    {
        if ($this->hardSkills->removeElement($hardSkill)) {
            // set the owning side to null (unless already changed)
            if ($hardSkill->getCandidat() === $this) {
                $hardSkill->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hobbie>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobbie $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
            $hobby->setCandidat($this);
        }

        return $this;
    }

    public function removeHobby(Hobbie $hobby): self
    {
        if ($this->hobbies->removeElement($hobby)) {
            // set the owning side to null (unless already changed)
            if ($hobby->getCandidat() === $this) {
                $hobby->setCandidat(null);
            }
        }
        return $this;
    }
    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            $annonce->removeFavorite($this);
        }

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
            $searchProfile->setCandidat($this);
        }

        return $this;
    }

    public function removeSearchProfile(SearchProfile $searchProfile): self
    {
        if ($this->searchProfiles->removeElement($searchProfile)) {
            // set the owning side to null (unless already changed)
            if ($searchProfile->getCandidat() === $this) {
                $searchProfile->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CandidatHasTechno>
     */
    public function getCandidatHasTechnos(): Collection
    {
        return $this->candidatHasTechnos;
    }

    public function addCandidatHasTechno(CandidatHasTechno $candidatHasTechno): self
    {
        if (!$this->candidatHasTechnos->contains($candidatHasTechno)) {
            $this->candidatHasTechnos->add($candidatHasTechno);
            $candidatHasTechno->setCandidat($this);
        }

        return $this;
    }

    public function removeCandidatHasTechno(CandidatHasTechno $candidatHasTechno): self
    {
        if ($this->candidatHasTechnos->removeElement($candidatHasTechno)) {
            // set the owning side to null (unless already changed)
            if ($candidatHasTechno->getCandidat() === $this) {
                $candidatHasTechno->setCandidat(null);
            }
        }

        return $this;
    }
}
