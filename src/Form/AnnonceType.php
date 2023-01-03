<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\ExternaticConsultant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use function Sodium\add;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('company', null, ['choice_label' => 'name',
                'required' => false])
            ->add('salaryMin')
            ->add('salaryMax')
            ->add('contractType')
            ->add('studyLevel')
            ->add('remote')
            ->add('workTime')
            ->add('createdAt', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('endingAt', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('techno', null, ['choice_label' => 'name'])
            ->add('description', CKEditorType::class, [
                'config_name' => 'light'
            ])
            ->add('author', EntityType::class, [
                'class' => ExternaticConsultant::class,
                "required" => false,
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
