<?php

namespace App\Form;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Email *',
            ])
            ->add('plainPassword', RepeatedType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe *',
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe *',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex ([
                        'pattern' => "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/",
                        'message' => "Le mot de passe doit contenir au moins une lettre majuscule ou minuscule, un chiffre et un caractère spécial"
                    ])
                ],
            ])
            ->add('isValid', CheckboxType::class, [
                'label' => 'Actif',
              /*  'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'actif' => 1
                ]*/
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles *',
                'choices' => [
                    'Ecurie' => 'ROLE_ECURIE',
                    'Gîte' => 'ROLE_GITE',
                ],
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de bien vouloir assigner un rôle'
                    ]),
                ],
            ])
            ->add('customer', CustomerType::class, [
                'context' => $options['context']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'context' => null,

        ]);
    }
}
