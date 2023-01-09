<?php

namespace App\Component;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent("annonce_form")]
class AnnonceForm extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'annonceField')]
    public ?Annonce $annonce = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AnnonceType::class, $this->annonce);
    }

    #[LiveAction]
    public function addTechno(): void
    {
        $this->formValues['techno'][] = '';
    }

    #[LiveAction]
    public function removeTechno(#[liveArg] int $index): void
    {
        unset($this->formValues['techno'][$index]);
    }
}
