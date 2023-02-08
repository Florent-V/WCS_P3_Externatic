<?php

namespace App\Component;

use App\Entity\User;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('user_update')]
class UserUpdate extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(fieldName: 'userField')]
    public ?User $user = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(UserUpdateType::class, $this->user);
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager
    ): void {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        /**
         * @var User $user
         */
        $user = $this->user;
        $this->submitForm();
        /**
         * @var User $userUpdate
         */
        $userUpdate = $this->getFormInstance()->getData();

        $user->setEmail($userUpdate->getEmail())
            ->setFirstname($userUpdate->getFirstname())
            ->setLastName($userUpdate->getLastName())
            ->setPhone($userUpdate->getPhone());
        $entityManager->persist($user);
        $entityManager->flush();

        $this->isSubmitted = true;
    }
}
