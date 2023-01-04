<?php

namespace App\Component;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\HttpFoundation\Response;

#[AsLiveComponent('experience_form')]
class ExperienceForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(fieldName: 'path')]
    public string $pathInfo = '';

    #[LiveProp(fieldName: 'experienceField')]
    public ?Experience $experience = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ExperienceType::class, $this->experience);
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): Response|null {
        // shortcut to submit the form with form values
        // if any validation fails, an exception is thrown automatically
        // and the component will be re-rendered with the form errors
        $this->submitForm();
        $experience = $this->getFormInstance()->getData();

        $user = $this->getUser();
        if ($user instanceof User) {
            $curriculum = $user->getCandidat()->getCurriculum();
            $experience->setCurriculum($curriculum);
            $experience->setIsFormation(false);
        }
        $entityManager->persist($experience);
        $entityManager->flush();
        $this->isSubmitted = true;

        if ($this->pathInfo !== $urlGenerator->generate('app_candidat_complete')) {
            $this->addFlash('success', 'Nouvelle expérience professionnelle ajoutée!');
            return $this->redirectToRoute('app_experience_index');
        }
        return null;
    }
}
