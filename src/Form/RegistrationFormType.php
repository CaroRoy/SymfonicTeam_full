<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

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
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'invalid_message' => 'Les champs "mot de passe" doivent être identiques',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Merci de confirmer ce mot de passe'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Un mot de passe est requis',
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
