<?php

namespace App\Component;

use App\Entity\Candidat;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('candidat_form')]
class CandidatForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(fieldName: 'candidatField')]
    public ?Candidat $candidat = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CandidatType::class, $this->candidat);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $candidat = $this->getFormInstance()->getData();
        $entityManager->persist($candidat);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
