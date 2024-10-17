<?php

namespace App\Form;

use App\Data\SearchUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'Compte',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'tous' => null,
                    'Activé' => true,
                    'Désactivé' => false,
                ],
                'placeholder' => false
            ])
            ->add('role', ChoiceType::class, [
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'choices'  => [
                    'Tous' => "",
                    'Écurie' => 'ROLE_ECURIE',
                    'Gîte' => 'ROLE_GITE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'label' => 'Rôles',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchUserData::class,
            'method' => 'GET',
            'csrf_protection' => 'false'
        ]);
    }
    public function  getBlockPrefix()
    {
        return'';
    }

}
