<?php

namespace App\Component;

use App\Entity\User;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('user_update')]
class UserUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public bool $isSubmitted = false;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(UserUpdateType::class, $this->getUser());
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $user = $this->getFormInstance()->getData();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->isSubmitted = true;

    }
}
