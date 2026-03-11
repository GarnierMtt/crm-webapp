<?php

namespace App\Form;

use App\Entity\Sites;
use App\Entity\Projets;
use App\Entity\Modeles;
use App\Entity\Materiels;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('fkModeles', EntityType::class, [
                'class' => Modeles::class,
                'choice_label' => 'libelle',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkSites', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkProjets', EntityType::class, [
                'required' => false,
                'class' => Projets::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiels::class,
        ]);
    }
}
