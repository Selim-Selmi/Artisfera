<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Sponsor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => [
                    'placeholder' => 'Quel est le nom de votre événement ?',
                ],
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Lieu',
                'attr' => [
                    'placeholder' => 'Où sera votre événement ?(ex: 75 Av.Habib Bourguiba Tunis )',
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => [
                    'placeholder' => 'Quand sera votre événement organisé ?',
                ],
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure',
                'attr' => [
                    'placeholder' => 'Quand commencera votre événement ?',
                ],
            ])
            ->add('nbParticipant', IntegerType::class, [
                'label' => 'Nombre de participants',
                'attr' => [
                    'placeholder' => 'Quel est le nombre maximal de participants ?',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajoutez une image (JPG, PNG)',
                ],
            ])
            ->add('sponsors', EntityType::class, [
                'class' => Sponsor::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Sponsors',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Sélectionnez un ou plusieurs sponsors',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
