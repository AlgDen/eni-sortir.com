<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfficherSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateDebut = new \DateTime();
        $dateDebut->modify('-1 week');
        $dateFin = new \DateTime();
        $dateFin->modify('+1 week');
        $builder
            ->add('lieu',EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu'
            ])
            ->add('nom', TextType::class, [
                'required' => false
            ])
            ->add('dateDebut', DateType::class, [
                'mapped' => false,
                'data' => $dateDebut,
                'format' => 'ddMMyyyy'
            ])
            ->add('dateFin', DateType::class, [
                'mapped' => false,
                'data' => $dateFin,
                'format' => 'ddMMyyyy'
            ])
            ->add('option1', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'mapped' => false
            ])
            ->add('option2', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'mapped' => false
            ])
            ->add('option3', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'mapped' => false
            ])
            ->add('option4', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
                'mapped' => false
            ])
            ->add('Rechercher', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
