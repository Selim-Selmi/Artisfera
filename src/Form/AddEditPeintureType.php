<?php

// src/Form/AddEditPeintureType.php

namespace App\Form;

use App\Entity\Peinture;
use App\Entity\Style;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class AddEditPeintureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('date_cr', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
            ])
            ->add('tableau', FileType::class, [
                'label' => 'Image de la peinture',
                'required' => false, // Permet de garder l'ancienne image si l'utilisateur ne télécharge pas une nouvelle
                'mapped' => false,  // L'image n'est pas directement mappée à l'entité
                'data_class' => null, // Ne lie pas la donnée à l'entité
            ])
            ->add('type', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'type_p',
                'label' => 'Style de peinture',
            ]);
           /*  ->add('Envoyer', SubmitType::class, [
                'label' => 'Enregistrer',
            ]); */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Peinture::class,
        ]);
    }
}
