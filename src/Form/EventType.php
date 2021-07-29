<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre :'])
            ->add('meetingDatetime', DateTimeType::class, ['label' => 'Date et heure :', 'date_widget' => 'single_text'])
            ->add('meetingPostalCode', TextType::class, ['label' => 'Code postal :'])
            ->add('meetingCity', TextType::class, ['label' => 'Ville :'])
            ->add('meetingPlace', TextType::class, ['label' => 'Lieu exact du rdv (ex: devant la gare, sur le parvis de l\'hôtel de ville...) :'])
            ->add('typeOfMusic', ChoiceType::class, [
                'label' => 'Style(s) de musique que tu proposes pour cette séance (CTRL+clic pour en sélectionner plusieurs) :',
                'required' => false,
                'choices' => [
                    'tous' => 'tous',
                    'rock\'n\'roll' => 'rock\'n\'roll',
                    'jazz' => 'jazz',
                    'blues' => 'blues',
                    'pop/rock' => 'pop/rock',
                    'classique' => 'classique',
                    'reggae' => 'reggae',
                    'metal' => 'metal',
                    'variété française' => 'variété française',
                    'autres' => 'autres'
                ],
                'empty_data' => ['tous'],
                'multiple' => true
            ])
            ->add('instrument', ChoiceType::class, [
                'label' => 'Instrument(s) que tu proposes pour cette séance (CTRL+clic pour en sélectionner plusieurs) :',
                'required' => false,
                'choices' => [
                    'tous' => 'tous',
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
                ],
                'empty_data' => ['tous'],
                'multiple' => true
            ])
            ->add('content', TextareaType::class, ['label' => 'Donne un peu plus de détails ici (comme par exemple la durée de la séance, les morceaux que tu proposes de jouer, ...) :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
