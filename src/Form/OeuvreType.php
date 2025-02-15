<?php
// OeuvreType.php

namespace App\Form;

use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\CeramicCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Sculpture' => 'sculpture',
                    'Poterie' => 'poterie',
                    'Vaisselle' => 'vaisselle',
                    'Mosaïque' => 'mosaïque',
                    'Autre' => 'autre',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('matiere', TextType::class, [
                'label' => 'Matière',
            ])
            ->add('couleur', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('dimensions', TextType::class, [
                'label' => 'Dimensions',
            ])
            ->add('createur', TextType::class, [
                'label' => 'Créateur',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,  // facultatif si l'image n'est pas obligatoire
                'mapped' => false,    // éviter que Symfony essaie de lier l'image à l'entité
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '2M',  // Limite de taille du fichier
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                    ])
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Art déco' => 'art_deco',
                    'Traditionnel' => 'traditionnel',
                    'Moderne' => 'moderne',
                    'Utilitaire' => 'utilitaire',
                    'Autre' => 'autre',
                ],
            ])

            

// Ajout du champ ceramicCollection
->add('ceramicCollection', EntityType::class, [
    'class' => CeramicCollection::class,
    'choice_label' => 'nom_c', // Remplace 'nom_c' par le nom du champ affiché
    'placeholder' => 'Choisissez une collection', 
    'required' => true,
])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}
