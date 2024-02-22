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
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',TextType::class, [
                'label' => 'Nom *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Téléphone *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('zipCode', textType::class, [
                'label' => 'Code Postal *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance *',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

            if($options['context'] === 'ecurie') {
                $builder->add('horseName', TextType::class, [
                    'label' => 'Nom du cheval *'
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
