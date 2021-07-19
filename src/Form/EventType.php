<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre :'])
            ->add('content', TextType::class)
            ->add('meetingDatetime', DateTimeType::class, ['label' => 'Date et heure :', 'date_widget' => 'single_text'])
            ->add('meetingPostalCode', TextType::class, ['label' => 'Code postal :'])
            ->add('meetingCity', TextType::class, ['label' => 'Ville :'])
            ->add('meetingPlace', TextType::class, ['label' => 'Lieu exact du rdv (ex: devant la gare, sur le parvis de l\'hôtel de ville...) :'])
            ->add('typeOfMusic', TextType::class, ['label' => 'Style(s) de musique que tu proposes :'])
            ->add('instrument', TextType::class, ['label' => 'Instrument(s) souhaités pour cette séance :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
