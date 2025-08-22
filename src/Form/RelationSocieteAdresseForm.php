<?php

namespace App\Form;

use App\Entity\Societe;
use App\Entity\RelationSocieteAdresse;
use App\Entity\Adresse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelationSocieteAdresseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomsite')
            ->add('description')
            ->add('societe', EntityType::class, [
                'class' => Societe::class,
                'choice_label' => 'name',
            ])
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'nomVoie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RelationSocieteAdresse::class,
        ]);
    }
}