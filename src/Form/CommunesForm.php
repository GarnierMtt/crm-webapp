<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Communes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('codePostal')
            ->add('fkPays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Communes::class,
        ]);
    }
}
