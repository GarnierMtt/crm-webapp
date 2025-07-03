<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\LienFibre;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienFibreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreFibres')
            ->add('distance')
            ->add('attenuation')
            ->add('referenceFibre')
            ->add('referenceOperateur')
            ->add('referenceLiaison')
            ->add('dateLivraison')
            ->add('dateActivation')
            ->add('lienActive')
            ->add('pointA', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'id',
            ])
            ->add('pointB', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'id',
            ])
            ->add('projet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LienFibre::class,
        ]);
    }
}
