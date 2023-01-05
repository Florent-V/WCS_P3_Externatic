<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret')
            ->add('name')
            ->add('logo')
            ->add('zipCode')
            ->add('address')
            ->add('city')
            ->add('phoneNumber')
            ->add('contactName')
            ->add('size')
            ->add('information')
            ->add('externaticConsultant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
