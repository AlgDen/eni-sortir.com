<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,[
                'label' => 'Nom de la sortie',
            ])
            ->add('date',null,[
                'label' => 'Date et heure de la sortie',
            ])
            ->add('dateLimiteInscription',null,[
                'label' => 'Date limite d\'inscription',
            ])
            ->add('nbParticipant',null,[
                'label' => 'Nombre de places',
            ])
            ->add('duree',null,[
                'label' => 'Durée',
            ])
            ->add('note',TextareaType::class,[
                'label' => 'Description et infos',
            ])
            ->add('lieu',EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => new Lieu(),
                'label' => 'Lieu'
            ])
            ->add('ville',null,[
                'label' => 'Ville',
                'disabled' => true,
                'mapped' => false
            ])
            ->add('rue',null,[
                'label' => 'Rue',
                'disabled' => true,
                'mapped' => false
            ])
            ->add('cp',null,[
                'label' => 'Code Postal',
                'disabled' => true,
                'mapped' => false
            ])
            ->add('latitude',null,[
                'label' => 'Latitude',
                'disabled' => true,
                'mapped' => false
            ])
            ->add('longitude',null,[
                'label' => 'Longitude',
                'disabled' => true,
                'mapped' => false
            ])
            ->add('enregistrer',ButtonType::class,[
                'label' => 'Enregistrer'
                ]
            )
            ->add('publier',ButtonType::class,[
                    'label' => 'Publier la sortie'
                ]
            )
            ->add('annuler',ButtonType::class,[
                    'label' => 'Annuler'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
