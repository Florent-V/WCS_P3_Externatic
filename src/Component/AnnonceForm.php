<?php

namespace App\Component;

use App\Entity\Annonce;
use App\Entity\Techno;
use App\Form\AnnonceType;
use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[AsLiveComponent("annonce_form")]
class AnnonceForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        private readonly TechnoRepository $technoRepository
    ) {
    }

    #[LiveProp(
        writable: true,
        dehydrateWith: 'dehydrateWith',
        fieldName: 'annonceField'
    )]
    #[Assert\Valid]
    public ?Annonce $annonce = null;

    #[LiveProp(writable: true)]
    public ?string $newTechnoName = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AnnonceType::class, $this->annonce);
    }

    public function dehydrateWith(): void
    {
    }

    #[LiveAction]
    public function addTechno(): void
    {
        if ($this->newTechnoName) {
            $techno = new Techno();
            $techno->setName($this->newTechnoName);
            $this->technoRepository->save($techno, true);
            $this->newTechnoName = null;
        }
    }
}
