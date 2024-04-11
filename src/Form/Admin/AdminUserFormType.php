<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail *',
            ])
            ->add('roles', ChoiceType::class,[
                'label' => 'Rôles *',
                'choices' => [
                    'Ecurie' => 'ROLE_ECURIE',
                    'Gîte' => 'ROLE_GITE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('isValid', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
                /*  'expanded' => true,
                  'multiple' => true,
                  'choices' => [
                      'actif' => 1
                  ]*/
            ])
            ->add('customer', AdminCustomerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
