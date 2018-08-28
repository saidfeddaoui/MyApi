<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre ',])
            ->add('_img', FileType::class, [
                'label' => 'Image ',
                'required' => false,
                'mapped' => false,
            ])
            ->add('content', TextareaType::class, ['label' => 'Contenu',])
            ->add('telephone', TextType::class, ['label' => 'Téléphone ',])
            ->add('email', TextType::class, ['label' => 'Adresse Email ',])
            ->add('title_ar', TextType::class, [
                'label' => 'العنوان',
                'required' => false,
                'mapped' => false,
            ])
            ->add('content_ar', TextareaType::class, [
                'label' => 'المحتوى',
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