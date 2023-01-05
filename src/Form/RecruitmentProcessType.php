<?php

namespace App\Form;

use App\Entity\RecruitmentProcess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecruitmentProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('information')
            ->add('rate')
            ->add('createdAt')
            ->add('candidat')
            ->add('annonce')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecruitmentProcess::class,
        ]);
    }
}
