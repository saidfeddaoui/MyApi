<?php

namespace App\Form;

use App\Entity\MarqueVehicule;
use App\Entity\ModeleVehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModeleVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom',])
            ->add('marque', EntityType::class, array(
                'class' => MarqueVehicule::class,
                'label' => 'Marque',
                'choice_label' => 'nom',
                'placeholder' => 'Veuillez choisir une marque',
                'required' => true
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModeleVehicule::class,
        ]);
    }
}
