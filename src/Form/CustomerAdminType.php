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

class CustomerAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',TextType::class, [
                'label' => 'Nom *',
            ])
            ->add('firstName', TextType::class,[
                'label' => 'prénom *',
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Téléphone *',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse *',
            ])
            ->add('zipCode', textType::class, [
                'label' => 'Code Postal *',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance *',
            ])
            ->add('horseName', TextType::class, [
                    'label' => 'Nom du cheval *',
                    'required' => false,
                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
