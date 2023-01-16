<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\ExternaticConsultant;
use PHPStan\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Numéro de Siret',
                'attr' => ['placeholder' => '362 521 879 00034']
            ])
            ->add('name', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Nom de l\'entreprise',
                'attr' => ['placeholder' => 'Sensio Labs']
            ])
            ->add('logoFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
                'row_attr' => ['class' => 'mb-3'],
                'label' => 'Image/Logo de l\'entreprise',
                'attr' => ['placeholder' => 'Ajouter un fichier']
            ])
            ->add('address', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Adresse',
                'attr' => ['placeholder' => '5 rue de la paix']
            ])
            ->add('zipCode', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Code Postal',
                'attr' => ['placeholder' => '75 000']
            ])
            ->add('city', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Paris']
            ])
            ->add('phoneNumber', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Numéro de Téléphone',
                'attr' => ['placeholder' => '+33 6 86...']
            ])
            ->add('contactName', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Nom du contact',
                'attr' => ['placeholder' => 'Pierre Richard']
            ])
            ->add('size', TextType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Taille de l\'entreprise',
                'attr' => ['placeholder' => '1000']
            ])
            ->add('information', TextareaType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Information supplémentaire',
                'attr' => ['placeholder' => 'Information à noter']
            ])
            ->add('externaticConsultant', EntityType::class, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Consultant Associé',
                'class' => ExternaticConsultant::class,
                'choice_label' => function (ExternaticConsultant $consultant) {
                    return $consultant->getUser()->getFirstname();
                },
                'multiple' => false,
                'expanded' => false,
                'by_reference' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
