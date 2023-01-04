<?php

namespace App\Component;

use App\Entity\Hobbie;
use App\Entity\User;
use App\Form\HobbieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('hobby_form')]
class HobbyForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    protected function instantiateForm(): FormInterface
    {
        $hobby = new Hobbie();
        return $this->createForm(HobbieType::class, $hobby);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $hobby = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $curriculum = $user->getCandidat()->getCurriculum();
            $hobby->setCurriculum($curriculum);
        }
        $entityManager->persist($hobby);
        $entityManager->flush();
        $this->isSubmitted = true;
    }
}
