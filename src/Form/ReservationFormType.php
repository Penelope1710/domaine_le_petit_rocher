<?php

namespace App\Form;

use App\Entity\Reservation;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => "La date d'arrivée doit être ultérieures à {{ value }}."
                    ]),
                    new NotBlank()
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de départ * : ',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'propertyPath' => "parent.all[startDate].data",
                        'message' => 'La date de fin doit être ultérieure à la date d\'arrivée.'
                    ]),
                    new NotBlank(),
                ],
            ])
            ->add('horseNb', ChoiceType::class, [
                'label' =>'Nb de chevaux : ',
                'expanded' => false,
                'multiple' => false,
                'choices' => [
                    'Aucun' => null,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ]
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
