<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmergencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre',])
            ->add('title_ar', TextType::class, [
                'label' => 'العنوان',
                'required' => true,
                'mapped' => false,
            ])
            ->add('_icn', FileType::class, [
                'label' => 'Icône ',
                'required' => false,
                'mapped' => false,
            ])
            ->add('subTitle', TextType::class, ['label' => 'Nuémro Téléphone'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'validation_groups' => array('emergency')
        ]);
    }

}