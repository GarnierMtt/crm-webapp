<?php

namespace App\Form;

use App\Form\Type\DateSelectorType;
use App\Entity\Sites;
use App\Entity\Projets;
use App\Entity\LiensFibre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiensFibreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreFibres', null, [
                'required' => true,
            ])
            ->add('distance')
            ->add('attenuation')
            ->add('referenceFibre')
            ->add('referenceOperateur')
            ->add('referenceLiaison')
            ->add('dateLivraison', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('dateActivation', DateSelectorType::class, [
                'required' => false,
            ])
            ->add('pointA', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('pointB', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])//*/
            ->add('fkProjets', EntityType::class, [
                'required' => false,
                'class' => Projets::class,
                'choice_label' => 'nom',
                'placeholder' => ' - - - - -',
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LiensFibre::class,
        ]);
    }
}
