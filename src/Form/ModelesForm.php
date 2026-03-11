<?php

namespace App\Form;

use App\Entity\Types;
use App\Entity\Modeles;
use App\Entity\Marques;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('numeroSerie')
            ->add('fkTypes', EntityType::class, [
                'class' => Types::class,
                'choice_label' => 'libelle',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkMarques', EntityType::class, [
                'class' => Marques::class,
                'choice_label' => 'libelle',
                'placeholder' => ' - - - - -',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modeles::class,
        ]);
    }
}
