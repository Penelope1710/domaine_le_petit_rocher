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
                'required' => true
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom *',
                'required' => true
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Téléphone *',
                'required' => true
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse *',
                'required' => true
            ])
            ->add('zipCode', textType::class, [
                'label' => 'Code Postal *',
                'required' => true
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'required' => true
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance *',
                'required' => true
            ]);

            if($options['context'] === 'ecurie') {
                $builder->add('horseName', TextType::class, [
                    'label' => 'Nom du cheval *',
                    'required' => true,
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'context' => null,
        ]);
    }
}
