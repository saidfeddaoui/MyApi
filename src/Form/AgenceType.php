<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raison_sociale',TextType::class,['label' => 'Raison sociale'])
            ->add('adresse', TextareaType::class,['label' => 'Adresse'])
            ->add('nomville',TextType::class,['label'=>'Nom Ville'])
            ->add('tel',TextType::class,['label'=>'Tél'])
            ->add('responsable',TextType::class,['label'=>'Responsable'])
            ->add('coordinates',TextType::class,['label'=>'GPS'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}
