<?php

namespace App\Component;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('experience_form')]
class ExperienceForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;



    protected function instantiateForm(): FormInterface
    {
        $experience = new Experience();
        return $this->createForm(ExperienceType::class, $experience);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $experience = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $curriculum = $user->getCandidat()->getCurriculum();
            $experience->setCurriculum($curriculum);
        }
        $entityManager->persist($experience);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
