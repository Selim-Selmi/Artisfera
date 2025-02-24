<?php

namespace App\Form;

use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use App\Form\DataTransformer\FilePathToFileTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\DataTransformerInterface;


class AddEditMusiqueType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            //->add('artistId',HiddenType::class )
            ->add('artistName')
            ->add('genre')
            ->add('dateSortie', DateTimeType::class, [
                'widget' => 'single_text', 
                'data' => new \DateTime(),
                'html5' => true,
                'attr' => ['style' => 'display:none;'],
            ])
            ->add('cheminFichier', FileType::class, [
                'label' => 'Fichier', 
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'input']
            ])
           
            ->add('photo', FileType::class, [
                'label' => 'Photo', 
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'input']
            ])
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
