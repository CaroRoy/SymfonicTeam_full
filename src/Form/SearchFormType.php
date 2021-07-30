<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                    'accordéon' => 'accordéon',
                    'banjo' => 'banjo',
                    'basse' => 'basse',
                    'batterie' => 'batterie',
                    'contrebasse' => 'contrebasse',
                    'cornemuse' => 'cornemuse',
                    'flûte' => 'flûte',
                    'guitare' => 'guitare',
                    'harmonica' => 'harmonica',
                    'jumbo' => 'jumbo',
                    'percussions' => 'percussions',
                    'piano' => 'piano',
                    'saxophone' => 'saxophone',
                    'synthé' => 'synthé',
                    'trombonne' => 'trombonne',
                    'trompette' => 'trompette',
                    'tubas' => 'tubas',
                    'ukulele' => 'ukulele',
                    'violon' => 'violon',
                    'violoncelle' => 'violoncelle',
                    'autres' => 'autres'
                ]
            ])
            ->add('typeOfMusic', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => 'style de musique',
                'choices' => [
                    'blues' => 'blues',
                    'classique' => 'classique',
                    'jazz' => 'jazz',
                    'metal' => 'metal',
                    'pop/rock' => 'pop/rock',
                    'reggae' => 'reggae',
                    'rock\'n\'roll' => 'rock\'n\'roll',
                    'variété française' => 'variété française',
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
            // on utilise la méthode GET pour transmettre les paramètres de recherche dans l'URL
            'method' => 'GET',
            // on désactive les protection CSRF car on est dans un simple formulaire de recherche
            'csrf_protection' => false
        ]);
    }

    // on retire les préfixes pour que les paramètres de recherche s'affichent directement dans l'URL sans leur titre
    public function getBlockPrefix()
    {
        return '';
    }

}