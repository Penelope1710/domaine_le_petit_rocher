<?php

namespace App\Form\Admin;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class UnavailabilityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de début * :',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => "La date de début doit être ultérieure à {{ value }}."
                    ]),
                    new NotBlank()
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin * : ',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'propertyPath' => "parent.all[startDate].data",
                        'message' => 'La date de fin doit être ultérieure à la date de début.'
                    ]),
                    new NotBlank(),
                ],
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
