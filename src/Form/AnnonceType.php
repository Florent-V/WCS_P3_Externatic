<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\ExternaticConsultant;
use App\Entity\Techno;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Titre']
            ])
            ->add('salaryMin', null, [
                'label' => 'Salaire Min',
                'attr' => ['placeholder' => 'Salaire Min'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('salaryMax', null, [
                'label' => 'Salaire Max',
                'attr' => ['placeholder' => 'Salaire Max'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('contractType', null, [
                'label' => 'Type de contrat (ex:Alternance, CDI, etc...)',
                'attr' => ['placeholder' => 'contractType'],
                'row_attr' => ['class' => 'form-floating mb-3'],
                ])
            ->add('studyLevel', null, [
                'label' => 'Niveau d\'étude',
                'attr' => ['placeholder' => 'studyLevel'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('workTime', null, [
                'label' => 'Durée',
                'attr' => ['placeholder' => 'Durée'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('company', null, [
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Entreprise',
                'attr' => ['placeholder' => 'Entreprise'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('remote', null, [
                'label' => 'Remote',
                'attr' => ['placeholder' => 'Remote'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('endingAt', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date d\'éxpiration',
                'attr' => ['placeholder' => 'Date d\'éxpiration'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ))
            ->add('techno', EntityType::class, [
                'label' => 'Hard Skills',
                'attr' => ['placeholder' => 'Hard Skills'],
                'row_attr' => ['class' => 'form-floating mb-3 techno'],
                'class' => Techno::class,
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',

            ])
            ->add('description', CKEditorType::class, [
                'config_name' => 'light',
                'config'      => ['editorplaceholder' => "Décrivez votre annonce..."]
            ])
            ->add('author', EntityType::class, [
                'class' => ExternaticConsultant::class,
                "required" => true,
                'choice_label' => function (ExternaticConsultant $consultant) {
                    return $consultant->getUser()->getFirstname();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
