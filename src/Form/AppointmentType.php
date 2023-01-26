<?php

namespace App\Form;

use App\Entity\Appointement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
            ])
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'RDV RH avec M DUPONT']
            ])
            ->add('length', DateIntervalType::class, [
                'label' => 'Durée du rdv',
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => true,
                'with_minutes' => true,
                'labels' => [
                    'hours' => 'Heures :',
                    'minutes' => 'Minutes :',
                ],
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Externatic Bordeaux...'],
                'row_attr' => ['class' => 'mb-3 mt-3'],
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du RDV',
                'attr' => [
                    'placeholder' => 'Votre prise de note...',
                    ],
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Publier',
                'row_attr' => ['class' => 'd-flex justify-content-center']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointement::class,
        ]);
    }
}
