<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de début * :',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir entrer une date de début',
        ])
                ],
            ])

            ->add('endDate', DateType::class, [
                'label' => 'Date de fin * : ',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir entrer une date de fin',
        ])
    ],
            ])
            ->add('horseNb', IntegerType::class, [
                'label' =>'Nb de chevaux : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
