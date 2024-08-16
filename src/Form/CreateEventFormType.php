<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'évènement *',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez indiquer un nom pour l'évènement"
                    ]),
                ]
         ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de l\'évènement *',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Heure de de l\'évènement *',
                'placeholder' => [
                    'hour' => 'Heures', 'minute' => 'Minutes'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('deadLine', DateType::class, [
                'label' => 'Date limite d\'inscription *',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('eventDetails', TextareaType::class, [
                'label' => 'Détails de l\'évènement *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Catégorie *',
                'placeholder' => 'Choisissez une catégorie',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,

        ]);
    }
}
