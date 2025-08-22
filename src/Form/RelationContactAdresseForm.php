<?php

namespace App\Form;

use App\Entity\RelationContactAdresse;
use App\Entity\Contact;
use App\Entity\Adresse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelationContactAdresseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role')
            ->add('notes')
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'nom',
            ])
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'nomSite',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RelationContactAdresse::class,
        ]);
    }
}
