<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSite')
            ->add('societe', EntityType::class, [
                'class' => Societe::class,
                'choice_label' => 'nom',
            ])
            ->add('pays')
            ->add('codePostal')
            ->add('commune')
            ->add('numeroVoie')
            ->add('nomVoie')
            ->add('complement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
