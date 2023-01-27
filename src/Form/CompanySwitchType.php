<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\ExternaticConsultant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanySwitchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => function (Company $company) {
                    return $company->getName();
                },
                'group_by' => function (Company $company) {
                    return $company->getExternaticConsultant()->getUser()->getFirstname() . " " .
                        $company->getExternaticConsultant()->getUser()->getLastName();
                }
            ])
            ->add('consultant', EntityType::class, [
                'class' => ExternaticConsultant::class,
                'choice_label' => function (ExternaticConsultant $consultant) {
                    return $consultant->getUser()->getFirstname() . " " . $consultant->getUser()->getLastName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
