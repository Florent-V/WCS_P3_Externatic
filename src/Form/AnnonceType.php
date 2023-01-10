<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\ExternaticConsultant;
use App\Repository\AnnonceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ])
            ->add('salaryMin', MoneyType::class, [
                'label' => 'Salaire Min',
                'attr' => ['placeholder' => 'Salaire Min'],
                'row_attr' => ['class' => 'form-floating'],
            ])
            ->add('salaryMax', MoneyType::class, [
                'label' => 'Salaire Max',
                'attr' => ['placeholder' => 'Salaire Max'],
                'row_attr' => ['class' => 'form-floating mb-3'],
            ])
            ->add('contractType', ChoiceType::class, [
                'choices' => $this->fetchingContractTypes(),
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'contracts']
            ])
            ->add('studyLevel', null, [
                'label' => 'Niveau d\'étude',
                'attr' => ['placeholder' => 'studyLevel'],
                'row_attr' => ['class' => 'form-floating mb-3 mt-3'],
            ])
            ->add('workTime', null, [
                'label' => 'Temps de travail',
                'attr' => ['placeholder' => 'Durée'],
                'row_attr' => ['class' => 'form-floating mb-3'],
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
            ->add('techno', CollectionType::class, [
                'entry_type' => TechnoAnnonceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            /*            ->add('description', CKEditorType::class, [
                            'config_name' => 'light',
                            'config' => ['editorplaceholder' => "Décrivez votre annonce..."]
                        ])*/
            ->add('description', TextAreaType::class, [])
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
