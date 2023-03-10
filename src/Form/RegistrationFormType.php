<?php

namespace App\Form;

use App\Entity\User;
use App\Security\PasswordConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'purify_html' => true,
            ])
            ->add('firstname', null, [
                'purify_html' => true,
                'required' => true,
            ])
            ->add('lastName', null, [
                'purify_html' => true,
                'required' => true,
            ])
            ->add('phone', null, [
                'purify_html' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'attr' => [
                    'autocomplete' => 'password'
                ],
                'first_options' => [
                    'constraints' => [
                        new PasswordConstraint(),
                    ],
                    'label' => 'Mot de passe',
                    'row_attr' => ['class' => 'form-floating mb-3'],
                    'attr' => ['placeholder' => '******'],
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe',
                    'row_attr' => ['class' => 'form-floating mb-3'],
                    'attr' => ['placeholder' => '******'],
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
