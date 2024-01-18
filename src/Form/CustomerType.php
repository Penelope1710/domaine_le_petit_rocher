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
//                'attr' => ["class" => "col-auto form-control" ],
                'label' => 'Nom',
                /*'label_attr' => [
                    'class' => 'col-form-label'
                    ],*/
            ])
            ->add('firstName', TextType::class,[
//                'attr' => ["class" => "col-auto form-control" ],
                'label' => 'Prénom',
                /*'label_attr' => [
                    'class' => 'col-form-label'
                    ],*/
            ])
            ->add('phoneNumber', TextType::class,[
//                'attr' => ["class" => "form-control" ],
                'label' => 'Téléphone',
               /* 'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
            ])
            ->add('address', TextType::class, [
//                'attr' => ["class" => "form-control" ],
                'label' => 'Adresse',
               /* 'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
            ])
            ->add('zipCode', textType::class, [
//                'attr' => ["class" => "form-control" ],
                'label' => 'Code Postal',
                /*'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
            ])
            ->add('city', TextType::class, [
//                'attr' => ["class" => "form-control" ],
                'label' => 'Ville',
               /* 'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
            ])
            ->add('horseName', TextType::class, [
//                'attr' => ["class" => "form-control" ],
                'label' => 'Nom du cheval',
               /* 'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
            ])
            ->add('birthDate', BirthdayType::class, [
//                'attr' => ["class" => "form-control" ],
                'label' => 'Date de naissance',
               /* 'label_attr' => [
                    'class' => 'form-label mt-4'
                    ]*/
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
