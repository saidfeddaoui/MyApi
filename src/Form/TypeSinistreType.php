<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeSinistreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre: ',])
            ->add('_icn', FileType::class, [
                'label' => 'Icône ',
                'required' => true,
                'mapped' => false,
            ])

            ->add('_img', FileType::class, [
                'label' => 'Icône(Active) ',
                'required' => false,
                'mapped' => false,
            ])
            ->add('title_ar', TextType::class, [
                'label' => 'العنوان',
                'required' => true,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}