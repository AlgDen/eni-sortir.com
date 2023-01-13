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
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('date',DateTimeType::class,[
                'label' => 'Date et heure de la sortie',
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('dateLimiteInscription',null,[
                'label' => 'Date limite d\'inscription',
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('nbParticipant',IntegerType::class,[
                'label' => 'Nombre de places',
                'constraints' => [new Positive()],
                'attr' => [
                    'min' => 1,
                    'style' => 'width: 80px',
                    'class' => 'bg-transparent'
                ]
            ])
            ->add('duree',IntegerType::class,[
                'label' => 'Durée',
                'constraints' => [new Positive()],
                'attr' => [
                     'min' => 1,
                     'style' => 'width: 80px',
                    'class' => 'bg-transparent'
                ]
            ])
            ->add('note',TextareaType::class,[
                'label' => 'Description et infos',
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('lieu',EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'choice_attr' => function (Lieu $choice, $key, $value) {
                    return ['data-idLieu' => $choice->getId()];
                },
                'label' => 'Lieu',
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('ville',TextType::class,[
//                'class' => Ville::class,
                'label' => 'Ville',
                'disabled' => true,
                'mapped' => false,
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('rue',null,[
                'label' => 'Rue',
                'disabled' => true,
                'mapped' => false,
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('cp',null,[
                'label' => 'Code Postal',
                'disabled' => true,
                'mapped' => false,
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('latitude',null,[
                'label' => 'Latitude',
                'disabled' => true,
                'mapped' => false,
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('longitude',null,[
                'label' => 'Longitude',
                'disabled' => true,
                'mapped' => false,
                'attr' => ['class' => 'bg-transparent']
            ])
            ->add('enregistrer',SubmitType::class,[
                'label' => 'Enregistrer'
                ]
            )
            ->add('publier',SubmitType::class,[
                    'label' => 'Publier la sortie'
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
