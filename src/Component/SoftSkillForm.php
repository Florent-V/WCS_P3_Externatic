<?php

namespace App\Component;

use App\Entity\SoftSkill;
use App\Entity\User;
use App\Form\SoftSkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('softskill_form')]
class SoftSkillForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    protected function instantiateForm(): FormInterface
    {
        $softskill = new SoftSkill();
        return $this->createForm(SoftSkillType::class, $softskill);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $softskill = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $skills = $user->getCandidat()->getCurriculum()->getSkills();
            $softskill->setSkills($skills);
        }
        $entityManager->persist($softskill);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
