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
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date d\'arrivée * :',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir entrer une date de début',
        ]),
//                    new GreaterThanOrEqual(['value' => 'today', 'message' => 'La date de début doit être ultérieure à la date actuelle.'])
                ],
            ])

            ->add('endDate', DateType::class, [
                'label' => 'Date de départ * : ',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir entrer une date de fin',
        ]),
//                    new GreaterThanOrEqual(['propertyPath' => 'startDate', 'message' => 'La date de fin doit être ultérieure à la date de début.'])
    ],
            ])
            ->add('horseNb', IntegerType::class, [
                'label' =>'Nb de chevaux : ',
                'required' => false,
                'help' => 'Optionnel'
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
