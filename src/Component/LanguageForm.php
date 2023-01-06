<?php

namespace App\Component;

use App\Entity\Language;
use App\Entity\User;
use App\Form\LanguageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('language_form')]
class LanguageForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    protected function instantiateForm(): FormInterface
    {
        $language = new Language();
        return $this->createForm(LanguageType::class, $language);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $language = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $skills = $user->getCandidat()->getCurriculum()->getSkills();
            $language->setSkills($skills);
        }
        $entityManager->persist($language);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
