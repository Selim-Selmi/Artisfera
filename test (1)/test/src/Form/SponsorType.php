<?php

namespace App\Form;

use App\Entity\Sponsor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du sponsor',
                'attr' => ['class' => 'form-control', 'novalidate' => 'novalidate'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de sponsor',
                'attr' => ['class' => 'form-control', 'novalidate' => 'novalidate'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 'novalidate' => 'novalidate'],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control', 'novalidate' => 'novalidate'],
            ])
            ->add('siteWeb', UrlType::class, [
                'required' => false,
                'constraints' => [
                    new Url([
                        'message' => "L'URL saisie n'est pas valide. Assurez-vous d'inclure 'http://' ou 'https://'."
                    ])
                ],
                'attr' => ['placeholder' => 'https://www.example.com'], // Aide visuelle pour l'utilisateur
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo de l’événement',
                'mapped' => false,  // Ce champ n'est pas lié directement à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger un logo valide (JPEG, PNG, GIF).',
                    ])
                ],
            ])
            
            // ->add('logo', TextType::class, [
            //     'label' => 'Logo (URL ou chemin du fichier)',
            //     'required' => false,
            //     'attr' => ['class' => 'form-control', 'novalidate' => 'novalidate'],
            // ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant donné par le sponsor (€)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 0.01,
                    'novalidate' => 'novalidate',
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsor::class,
        ]);
    }
}
