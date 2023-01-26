<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\ExternaticConsultant;
use App\Entity\Techno;
use App\Repository\AnnonceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AnnonceType extends AbstractType
{
    public function __construct(private readonly AnnonceRepository $annonceRepository)
    {
    }

    public function fetchingContractTypes(): array
    {
        $contractTypeQuery = $this->annonceRepository->createQueryBuilder("a")
            ->select("distinct (a.contractType)")
            ->getQuery()
            ->getResult();

        $contractTypeFromDb = [];
        foreach ($contractTypeQuery as $contractType) {
            $contractTypeFromDb[ucfirst($contractType[1])] = $contractType[1];
        }
        ksort($contractTypeFromDb);
        return $contractTypeFromDb;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'row_attr' => ['class' => 'form-floating mb-3'],
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Titre']
            ])->add('salaryMin', MoneyType::class, [
                'label' => 'Salaire Min',
                'attr' => ['placeholder' => 'Salaire Min'],
                'row_attr' => ['class' => 'form-floating'],
            ])->add('salaryMax', MoneyType::class, [
                'label' => 'Salaire Max',
                'required' => false,
                'attr' => ['placeholder' => 'Salaire Max'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])->add('contractType', ChoiceType::class, [
                'choices' => $this->fetchingContractTypes(),
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'contracts']
            ])->add('contractDuration', DateIntervalType::class, [
                'label' => "Durée du contrat",
                'with_years' => true,
                'with_months' => true,
                'with_days' => true,
                'with_hours' => false,
                'labels' => [
                    'years' => 'Années',
                    'mouths' => 'mois',
                    'days' => 'jours',
                ],
            ])->add('studyLevel', null, [
                'label' => 'Niveau d\'étude',
                'attr' => ['placeholder' => 'studyLevel'],
                'row_attr' => ['class' => 'form-floating mb-3 mt-3'],
            ])->add('workTime', DateIntervalType::class, [
                'label' => 'Durée hebdomadaire',
                'with_years' => false,
                'with_months' => false,
                'with_days' => false,
                'with_hours' => true,
                'with_minutes' => true,
                'labels' => [
                    'hours' => 'Heures',
                    'minutes' => 'Minutes',
                ],
                'hours' => range(1, 50),

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
            ->add('endingAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date d\'éxpiration',
                'attr' => ['placeholder' => 'Date d\'éxpiration'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('techno', EntityType::class, [
                'label' => 'Languages',
                'class' => Techno::class,
                'choice_label' => 'name',
                'allow_extra_fields' => true,
                'expanded' => true,
                'multiple' => true,
                'label_attr' => ['class' => 'checkbox-inline']
            ])
            ->add('description', CKEditorType::class, [
                'attr' => ['data-ckeditor' => true],
                'config_name' => 'light',
                'config' => ['editorplaceholder' => "Décrivez votre annonce..."]
            ])
            ->add('author', EntityType::class, [
                'class' => ExternaticConsultant::class,
                "required" => true,
                'choice_label' => function (ExternaticConsultant $consultant) {
                    return $consultant->getUser()->getFirstname();
                }
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
            'data_class' => Annonce::class,
        ]);
    }
}
