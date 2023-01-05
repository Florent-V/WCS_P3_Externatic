<?php

namespace App\Component;

use App\Entity\Experience;
use App\Entity\User;
use App\Form\FormationType;
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

#[AsLiveComponent('formation_form')]
class FormationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $isSubmitted = false;

    #[LiveProp(fieldName: 'path')]
    public string $pathInfo = '';

    #[LiveProp(fieldName: 'formationField')]
    public ?Experience $formation = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(FormationType::class, $this->formation);
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
        $this->formValues = null;
        if ($this->pathInfo !== $urlGenerator->generate('app_candidat_complete')) {
            $this->addFlash('success', 'Nouvelle expérience professionnelle ajoutée!');
            return $this->redirectToRoute('app_experience_index');
        }
        return null;
    }
}
