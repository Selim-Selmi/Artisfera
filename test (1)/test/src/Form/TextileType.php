<?php

namespace App\Form;

use App\Entity\Textile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CollectionT;

class TextileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type')
            ->add('description')
            ->add('matiere')
            ->add('couleur')
            ->add('dimension')
            ->add('createur')
            ->add('imageFile', FileType::class, [
                'label' => 'Upload Image (JPG/PNG)',
                'mapped' => false, // Important: This prevents automatic mapping to the entity
                'required' => true, // Make it required
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG).',
                    ])
                ],
            ])
            ->add('technique')


            ->add('collection', EntityType::class, [
                'class' => CollectionT::class,
                'choice_label' => 'nom', // Adjust this to the field you want to display
                'placeholder' => 'Select a collection',
                'required' => true,
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Textile::class,
        ]);
    }
}
