<?php

namespace App\Form;

use App\Form\Type\DateSelectorType;
use App\Entity\Projets;
use App\Entity\Societes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('societeClient', EntityType::class, [
                'class' => Societes::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('dateDebut', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('dateFin', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
