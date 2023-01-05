<?php

namespace App\Component;

use App\Entity\HardSkill;
use App\Entity\User;
use App\Form\HardSkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('hardskill_form')]
class HardSkillForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    protected function instantiateForm(): FormInterface
    {
        $hardskill = new HardSkill();
        return $this->createForm(HardSkillType::class, $hardskill);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $hardskill = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $skills = $user->getCandidat()->getCurriculum()->getSkills();
            $hardskill->setSkills($skills);
        }
        $entityManager->persist($hardskill);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
