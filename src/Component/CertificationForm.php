<?php

namespace App\Component;

use App\Entity\Certification;
use App\Entity\User;
use App\Form\CertificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('certification_form')]
class CertificationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(fieldName: 'certificationField')]
    public ?Certification $certification = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CertificationType::class, $this->certification);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $certification = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $curriculum = $user->getCandidat()->getCurriculum();
            $certification->setCurriculum($curriculum);
        }
        $entityManager->persist($certification);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
