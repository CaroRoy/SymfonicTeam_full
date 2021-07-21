<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom :', 'required' => true])
            ->add('lastName', TextType::class, ['label' => 'Nom :', 'required' => true])
            ->add('avatar',FileType::class, ['label' => 'Photo de profil :', 'mapped' => false, 'required' => false])
            ->add('birthdate', DateType::class, ['label' => 'Date de naissance :', 'required' => true, 'widget' => 'single_text'])
            ->add('street', TextType::class, ['label' => 'Adresse :', 'required' => true])
            ->add('postalCode', TextType::class, ['label' => 'Code postal :', 'required' => true])
            ->add('city', TextType::class, ['label' => 'Ville :', 'required' => true])
            ->add('email', EmailType::class, ['label' => 'Adresse e-mail :', 'required' => true])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Veuillez accepter les CGU',
                'mapped' => false,
                'required'=> false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les CGU',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
