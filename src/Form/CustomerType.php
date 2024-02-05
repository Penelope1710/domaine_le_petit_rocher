<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',TextType::class, [
                'label' => 'Nom *',
                'attr' => ["class" => "form-control" ],
                'row_attr' => ['class' => 'col-sm-6'],

            ])
            ->add('firstName', TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'prénom *',
                'row_attr' => ['class' => 'col-sm-6'],


            ])
            ->add('phoneNumber', TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Téléphone *',
                'row_attr' => ['class' => 'col-sm-6'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Adresse *',
                'row_attr' => ['class' => 'row mb-3'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('zipCode', textType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Code Postal *',
                'row_attr' => ['class' => 'row mb-3'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Ville *',
                'row_attr' => ['class' => 'row mb-3'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('horseName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom du cheval *',
                'mapped' => 'false',
                'row_attr' => ['class' => 'row mb-3'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('birthDate', BirthdayType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de naissance *',
                'row_attr' => ['class' => 'row mb-3'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,

        ]);
    }
}
