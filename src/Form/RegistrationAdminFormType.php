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
                'attr' => ['class' => 'form-control'],
                'label' => 'Email *',
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
                'row_attr' => ['class' => 'row mb-3'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'options' => [
                    'attr' => ['class' => 'form-control'
                    ],
                ],
                'required' => true,
                'first_options'  => [
                    'attr' => [
                        "class" => "form-control",
                    ],
                    'label' => 'Mot de passe *',
                    'label_attr' => ['class' => 'col-sm-2 col-form-label'],
                    'row_attr' => ['class' => 'row mb-3'],
                ],
                'second_options' => [
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Confirmer le mot de passe *',
                    'label_attr' => ['class' => 'col-sm-2 col-form-label'],
                    'row_attr' => ['class' => 'row mb-3'],
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
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles *',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Ecurie' => 'ROLE_ECURIE',
                    'Gîte' => 'ROLE_GITE',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add('customer', CustomerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}
