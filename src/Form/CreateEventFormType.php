<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\EventStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'évènement'
        ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de la sortie'
            ])
            ->add('deadLine', DateType::class, [
                'label' => 'Date limite d\'inscription'
            ])
            ->add('eventDetails', TextareaType::class, [
                'label' => 'Détails de l\'évènement'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Catégorie',
                'placeholder' => 'Choisissez une catégorie'
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
