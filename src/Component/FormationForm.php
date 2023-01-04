<?php

namespace App\Component;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('formation_form')]
class FormationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(dehydrateWith: 'dehydrateFormation', fieldName: 'formationField')]
    public ?Experience $formation = null;

    public function dehydrateFormation(): void
    {
    }

    protected function instantiateForm(): FormInterface
    {
        $this->formation = new Experience();
        return $this->createForm(FormationType::class, $this->formation);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $formation = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $curriculum = $user->getCandidat()->getCurriculum();
            $formation->setCurriculum($curriculum);
            $formation->setIsFormation(true);
        }
        $entityManager->persist($formation);
        $entityManager->flush();
        $this->isSubmitted = true;
        //unset($formation);
        $this->formValues = null;
    }
}
