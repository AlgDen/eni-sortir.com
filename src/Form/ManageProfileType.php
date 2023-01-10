<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManageProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'rounded-lg text-blue-700 h-10'
                ],
                ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'rounded-lg text-blue-700 h-10'
                ],
                ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'rounded-lg text-blue-700 h-10'
                ],
                ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'rounded-lg text-blue-700 h-10'
                ],
                ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'rounded-lg text-blue-700 h-10'
                ],
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_name' => 'first',
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'hash_property_path' => 'password',
                    // 'always_empty' => false,
                    'label_attr' => [
                        'class' => 'py-1'
                    ],
                    'attr' => [
                        'class' => 'rounded-lg text-blue-700 h-10'
                    ],
                ],
                'second_name' => 'second',
                'second_options' => [
                    'label' => 'Confirmation Mot de passe',
                    'label_attr' => [
                        'class' => 'py-1'
                    ],
                    'attr' => [
                        'class' => 'rounded-lg text-blue-700 h-10'
                    ],
                ],
                'required' => false,
                'mapped' => false,
                ])
            ->add('site', EntityType::class, [
                'placeholder' => 'Sélectionnez une option',
                'class' => Site::class,
                'choice_label' => 'nom',
                'required' => false,
                'label_attr' => [
                    'class' => 'text-night py-1'
                ],
                'attr' => [
                    'class' => 'text-night rounded-lg h-10'
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Ma photo',
                'required' => false,
                'label_attr' => [
                    'class' => 'py-1'
                ],
                'attr' => [
                    'class' => 'border-solid border-2 border-gray-700 rounded-lg h-10 bg-gray-700'
                ],
                ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
