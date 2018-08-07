<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre ',])
            ->add('content', TextareaType::class, ['label' => 'Description ',])
            ->add('nosGaranties', TextareaType::class, ['label' => 'Nos Garanties ',])
            ->add('nosPlus', TextareaType::class, ['label' => 'Nos Plus ',])
            ->add('_icn', FileType::class, [
                'label' => 'Icône ',
                'required' => false,
                'mapped' => false,
            ])
            ->add('_img', FileType::class, [
                'label' => 'Image ',
                'required' => false,
                'mapped' => false,
            ])
            ->add('title_ar', TextType::class, [
                'label' => 'العنوان',
                'required' => false,
                'mapped' => false,
            ])
            ->add('content_ar', TextareaType::class, [
                'label' => 'الوصف',
                'required' => false,
                'mapped' => false,
            ])

            ->add('nosGaranties_ar', TextareaType::class, [
                'label' => 'ضماناتنا',
                'required' => false,
                'mapped' => false,
            ])

            ->add('nosPlus_ar', TextareaType::class, [
                'label' => 'إضافاتنا',
                'required' => false,
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