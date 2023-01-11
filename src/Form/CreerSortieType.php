<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,[
                'label' => 'Nom de la sortie',
            ])
            ->add('date',DateTimeType::class,[
                'label' => 'Date et heure de la sortie',
            ])
            ->add('dateLimiteInscription',null,[
                'label' => 'Date limite d\'inscription',
            ])
            ->add('nbParticipant',IntegerType::class,[
                'label' => 'Nombre de places',
                'constraints' => [new Positive()],
                'attr' => [
                    'min' => 1,
                    'style' => 'width: 50px'
                ]
            ])
            ->add('duree',IntegerType::class,[
                'label' => 'Durée',
                'constraints' => [new Positive()],
                'attr' => [
                     'min' => 1,
                     'style' => 'width: 50px'
                ]
            ])
            ->add('note',TextareaType::class,[
                'label' => 'Description et infos',
            ])
            ->add('lieu',EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'choice_attr' => function (Lieu $choice, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['data-idLieu' => $choice->getId()];
                },
                'label' => 'Lieu'
            ])
            ->add('ville',TextType::class,[
//                'class' => Ville::class,
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
            ->add('enregistrer',SubmitType::class,[
                'label' => 'Enregistrer'
                ]
            )
            ->add('publier',SubmitType::class,[
                    'label' => 'Publier la sortie'
                ]
            )
            ->add('annuler',ButtonType::class,[
                    'label' => 'Annuler'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
