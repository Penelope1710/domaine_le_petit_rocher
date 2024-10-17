<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel *",
                'attr' => [
                    'placeholder' => "Indiquez votre mot de passe actuel",
                    'class' => 'form-control'
                ],
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe *',
                    'attr' => [
                        'placeholder' => "Choisissez votre nouveau mot de passe",
                        'class' => 'form-control'
                    ],
                    //encoder mot de passe et faire le lien avec notre propriété password dans l'entité User
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre nouveau mot de passe *',
                    'attr' => [
                        'placeholder' => "Confirmer votre nouveau mot de passe",
                        'class' => 'form-control'
                    ]
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
                        'pattern' => "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&,.])[A-Za-z\d@$!%*#?&,.]+$/",
                        'message' => "Le mot de passe doit contenir au moins une lettre majuscule ou minuscule, un chiffre et un caractère spécial"
                    ])
                ],
            ])

            //j'ajoute un écouteur d'évènement concernant la vérification du mdp actuel au moment du submit (je compare le mdp actuel entré et celui qui est en bdd)
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $formEvent) {

                //l'évènement va aller chercher le formulaire
                $passwordUserForm = $formEvent->getForm();
                $user = $passwordUserForm->getConfig()->getOptions()['data'];
                $passwordHasher = $passwordUserForm->getConfig()->getOptions()['passwordHasher'];
                //je récupère le mdp saisii par le user et je compare avec celui en bdd
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $passwordUserForm->get('actualPassword')->getData()
                );
                if (!$isValid) {
                    //on va chercher le champ concerné
                    $passwordUserForm->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme, veuillez vérifier votre saisie"));
                }

            })
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
