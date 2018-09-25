<?php

namespace App\Form;

use App\Entity\Version;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current_version', TextType::class, ['label' => 'Version courante  '])
            ->add('min_version', TextType::class, ['label' => 'Version min'])
            ->add('msg_info', TextareaType::class, ['label' => 'Message info '])
            ->add('msg_bloquer', TextareaType::class, ['label' => 'Message bloquer'])
            ->add('cache', CheckboxType::class, ['label' => 'Cache '])
            ->add('path', TextType::class, [
                'label' => 'Path ',
                'required' => false,
            ])
            ->add('version_code', TextType::class, [
                'label' => 'Code version ',
                'required' => false,
            ])
            ->add('os', TextType::class, ['label' => 'Os ',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Version::class,
        ]);
    }
}
