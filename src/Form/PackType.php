<?php

namespace App\Form;

use App\Entity\Pack;
use App\Entity\Societaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,['label'=>"Titre"])
            ->add('code',TextType::class,['label'=>"Code"])
            ->add('societaire',EntityType::class,[
                'class' => Societaire::class,
                'choice_label' => 'name',
                'label' => 'Type sociétaire',
                'placeholder' => 'Veuillez choisir le type sociétaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
        ]);
    }
}
