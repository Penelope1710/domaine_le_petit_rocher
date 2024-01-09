<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',null, [
                'label' => 'Nom'
            ])
            ->add('firstName', null,[
                'label' => 'Prénom'

            ])->add('phoneNumber', null,[
                'label' => 'Téléphone'
            ])
            ->add('address', null, [
                'label' => 'Adresse'
            ])
            ->add('zipCode', textType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', null, [
                'label' => 'Ville'

            ])->add('horseName', null, [
                'label' => 'Nom du cheval'
            ])
            ->add('dateOfBirth', null, [
                'label' => 'Date de naissance'
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
