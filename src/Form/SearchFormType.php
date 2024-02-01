<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('categories', EntityType::class,[
                'label' => 'Catégorie',
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Toutes',
            ])
            ->add('dateDebut', DateType::class, [
            'label' => 'Entre : ',
            'widget' => 'single_text',
            'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Et : ',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('activite', ChoiceType::class, [
                'label' => 'Evènements',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'tous' => 1,
                    'dont je suis l\'organisateur' => 2,
                    'auxquels je suis inscrit(e)' => 3
                ],
                'placeholder' => false
            ])
        ;

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => 'false'
        ]);
    }

    public function  getBlockPrefix()
    {
        return'';
    }
}