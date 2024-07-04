<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //pour creer un formulaire directement et le customiser
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre adresse email",
                'attr' => [
                    'placeholder' => "carloslechet@exemples.com"
                ]
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 30
                    ])
                ],
                'first_options'  => [
                    'label' => 'Choisissez votre mot de passe',
                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => "votre mot de passe"
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => "votre mot de passe"
                    ]
                ],
                'mapped' => false,

            ])
            ->add('firstname', TextType::class, [
                'label' => "Votre prénom",
                'attr' => [
                    'placeholder' => "Carlos"
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 20
                    ])
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => "Votre nom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 20
                    ])
                ],
                'attr' => [
                    'placeholder' => "Desneufmoulanos"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => "btn btn-outline-primary d-block mx-auto mt-5 px-3"
                ]
            ]) //pour creer un boutton de type submit 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
