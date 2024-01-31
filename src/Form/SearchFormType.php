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
                'multiple' => false
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
            ->add('choix1', CheckboxType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('choix2', CheckboxType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('choix3', CheckboxType::class, [
                'label' => false,
                'required' => false
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