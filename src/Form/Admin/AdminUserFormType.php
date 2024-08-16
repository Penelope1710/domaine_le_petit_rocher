<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                    'Gîte' => 'ROLE_GITE',
                    'Ecurie' => 'ROLE_ECURIE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('isValid', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
                /*  'expanded' => true,
                  'multiple' => true,
                  'choices' => [
                      'activé' => 1
                  ]*/
            ])
            ->add('contractFileName', FileType::class, [
                'label' => 'Contrat (PDF file)',
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false
            ])
            ->add('customer', AdminCustomerType::class,[
            'context' => $options['context']
        ]);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray): string {
                    if (empty($rolesAsArray) || null === $rolesAsArray) {
                        return '';
                    }
                    return $rolesAsArray[0];
                },
                function ($rolesAsString): array {
                    return (array)$rolesAsString;
                }
            ))
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'context' => null,
        ]);
    }
}
