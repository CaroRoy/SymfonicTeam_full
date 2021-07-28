<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'mots-clés']
            ])
            ->add('postalCode', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'code postal ou n° du département']
            ])
            ->add('dateMin', DateType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
                'input_format' => 'Y-m-d',
            ])
            ->add('dateMax', DateType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
                'input_format' => 'Y-m-d',
            ])

            ->add('instrument', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => 'instrument',
                'choices' => [
                    'guitare' => 'guitare',
                    'banjo' => 'banjo',
                    'ukulele' => 'ukulele',
                    'basse' => 'basse',
                    'harmonica' => 'harmonica',
                    'violoncelle' => 'violoncelle',
                    'violon' => 'violon',
                    'flûte' => 'flûte',
                    'accordéon' => 'accordéon',
                    'piano' => 'piano',
                    'synthé' => 'synthé',
                    'percussions' => 'percussions',
                    'jumbo' => 'jumbo',
                    'trompette' => 'trompette',
                    'cornemuse' => 'cornemuse'
                ]
            ])
            ->add('typeOfMusic', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => 'style de musique',
                'choices' => [
                    'rock\'n\'roll' => 'rock\'n\'roll',
                    'jazz' => 'jazz',
                    'blues' => 'blues',
                    'pop/rock' => 'pop/rock',
                    'classique' => 'classique',
                    'reggae' => 'reggae',
                    'metal' => 'metal',
                    'variété française' => 'variété française',
                    'autres' => 'autres'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // on utilise la class SearchData pour représenter les données du formulaire
            'data_class' => SearchData::class,
            // on envoie les paramètres de recherche dans l'URL avec la méthode GET
            'method' => 'GET',
            // on désactive les protection CSRF car on est dans un simple formulaire de recherche
            'csrf_protection' => false
        ]);
    }

    // on retire les préfixes pour que les paramètres de recherche s'affichent directement dans l'URL sans format particulier
    public function getBlockPrefix()
    {
        return '';
    }

}