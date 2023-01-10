<?php

namespace App\Component;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[AsLiveComponent("annonce_form")]
class AnnonceForm extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    public function __construct(
        private readonly TechnoRepository $technoRepository,
        private readonly AnnonceRepository $annonceRepository
    ) {
    }

    #[LiveProp(fieldName: 'annonceField')]
    #[Assert\Valid]
    public ?Annonce $annonce = null;

    #[LiveProp(writable: true)]
    public ?string $search = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AnnonceType::class, $this->annonce);
    }

    #[LiveAction]
    public function addTechno(#[liveArg] ?string $name): void
    {
        if ($name) {
            $this->annonceRepository->save($this->annonce, true);
            $techno = $this->technoRepository->findOneBy(['name' => $name]);
            $this->annonce->addTechno($techno);
            $this->annonceRepository->save($this->annonce, true);
            $this->formValues['techno'][] = ['name' => $name];
        } else {
            $this->formValues['techno'][] = [''];
        }
    }

    #[LiveAction]
    public function removeTechno(#[liveArg] int $index): void
    {
        unset($this->formValues['techno'][$index]);
    }

    public function getResults(): array
    {
        return $this->search ? $this->technoRepository->search($this->search) : [];
    }
}
