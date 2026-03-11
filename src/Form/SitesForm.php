<?php

namespace App\Form;

use App\Entity\Sites;
use App\Entity\Societes;
use App\Entity\Communes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('numeroVoie')
            ->add('nomVoie')
            ->add('complement')
            ->add('fkCommunes', EntityType::class, [
                'class' => Communes::class,
                'choice_label' => 'libelle',
                'placeholder' => ' - - - - -',
            ])
            ->add('fkSocietes', EntityType::class, [
                'class' => Societes::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sites::class,
        ]);
    }
}
