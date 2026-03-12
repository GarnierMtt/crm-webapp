<?php

namespace App\Form;

use App\Form\Type\DateSelectorType;
use App\Entity\Taches;
use App\Entity\Projets;
use App\Entity\Societes;
use App\Entity\Utilisateurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TachesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('dateDebut', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('dateFin', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('fkProjets', EntityType::class, [
                'class' => Projets::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkSocietes', EntityType::class, [
                'required' => false,
                'class' => Societes::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkUtilisateurs', EntityType::class, [
                'required' => false,
                'class' => Utilisateurs::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Taches::class,
        ]);
    }
}