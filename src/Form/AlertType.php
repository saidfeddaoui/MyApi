<?php

namespace App\Form;

use App\Entity\Alert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre ',])
            ->add('title_ar', TextType::class, ['label' => 'عنوان ','mapped'=>false])
            ->add('subTitle', TextType::class, ['label' => 'Sous titre ',])
            ->add('subTitle_ar', TextType::class, ['label' => 'عنوان فرعي ','mapped'=>false])
            ->add('description',TextareaType::class,['label'=>'Description '])
            ->add('description_ar',TextareaType::class,['label'=>'وصف','mapped'=>false])
            ->add('date_creation',DateType::class,
                ['label'=>'Date d\'ajout ',
                    'widget'=> 'single_text',
                    'format'=>'dd-MM-yyyy HH:mm',
                    'attr'=>array('autocomplete'=>'off'),
                    'required'=>true])
            ->add('date_expiration',DateType::class,
                ['label'=>'Date d\'expiration ',
                 'widget'=> 'single_text',
                 'format'=>'dd-MM-yyyy HH:mm',
                 'attr'=>array('autocomplete'=>'off'),
                 'required'=>true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
        ]);
    }
}
