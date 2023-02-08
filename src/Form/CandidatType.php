<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age', null)
            ->add('linkedIn', null, [
                'purify_html' => true,
            ])
            ->add('github', null, [
                'purify_html' => true,
            ])
            ->add('zipCode', null, [
                'purify_html' => true,
            ])
            ->add('address', null, [
                'purify_html' => true,
            ])
            ->add('city', null, [
                'purify_html' => true,
            ])
            ->add('description', null, [
                'purify_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
