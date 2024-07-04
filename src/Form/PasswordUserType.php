<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel",
                'attr' => [
                    'placeholder' => 'mot de passe actuel'
                ],
                'mapped' => false
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
                    'label' => 'Choisissez votre nouveau mot de passe',

                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => "nouveau mot de passe"
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => "confirmer nouveau mot de passe"
                    ]
                ],
                'mapped' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => "Modifier mot de passe",
                'attr' => [
                    'class' => "btn btn-outline-primary d-block mx-auto mt-5 px-3"
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                //recuperer le formulaire
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                // 1. recuperer le mot de passe saisi par l'utilisateur et le comparer à celui de la db
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );


                // 3. si c'est != envoyer une erreur
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme, veuillez verifier votre mot de passe"));
                }





            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
