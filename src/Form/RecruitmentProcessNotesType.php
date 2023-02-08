<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecruitmentProcessNotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('information', TextareaType::class, [
                'purify_html' => true,
                'label' => 'Vos notes',
                'attr' => [
                    'placeholder' => 'Votre prise de note...'
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Publier',
                'row_attr' => ['class' => 'd-flex justify-content-center']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
