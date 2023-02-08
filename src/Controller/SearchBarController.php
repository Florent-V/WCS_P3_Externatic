<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Techno;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class SearchBarController extends AbstractController
{
    public function searchBar(AnnonceRepository $annonceRepository, mixed $searchData): Response
    {
        $contractTypeFromDb = [];
        foreach (AnnonceType::CONTRACT_TYPE as $contractType) {
            $contractTypeFromDb[ucfirst($contractType)] = $contractType;
        }
        ksort($contractTypeFromDb);


        //Creating search engine
        $form = $this->createFormBuilder($searchData)
            ->add('searchQuery', TextType::class, [
                'attr' => [
                    'placeholder' => 'votre recherche'
                ],
                'required' => false,
            ])
            ->add('remote', ChoiceType::class, [
                'choices' => [
                    'Total/partiel' => true,
                    'Présentiel' => false,
                ],
                "required" => false,
            ])
            ->add('workTime', ChoiceType::class, [
                'choices' => [
                    'Plein temps' => true,
                    'Temps partiel' => false,
                ],
                "required" => false,
            ])
            ->add('period', ChoiceType::class, [
                'choices' => [
                    '1 jour' => 1,
                    '5 jours' => 5,
                    '2 semaines' => 15,
                ],
                "required" => false,
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                "required" => false,
            ])
            ->add('salaryMin', MoneyType::class, [
                'grouping' => false,
                "required" => false,
            ])
            ->add('contractType', ChoiceType::class, [
                'choices' => $contractTypeFromDb,
                "required" => false,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => '']
            ])
            ->add('techno', EntityType::class, [
                'class' => Techno::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'name',
            ])
            ->setMethod('GET')
            ->setAction($this->generateUrl('annonce_search_results'))
            ->getForm();

        return $this->renderForm('_include/_searchBar.html.twig', [
            'form' => $form
        ]);
    }
}
