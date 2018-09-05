<?php

namespace App\Form;

use App\Entity\Device;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('device_uid', TextType::class, ['label' => 'device uid '])
            ->add('model', TextType::class, ['label' => 'model '])
            ->add('version_code', TextType::class, ['label' => 'Version code '])
            ->add('firebase_token', TextType::class, ['label' => 'Firebase Token '])
            ->add('pushable', CheckboxType::class, ['label' => 'Pushable '])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude ',
                'required' => false,
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude ',
                'required' => false,
                ])
            ->add('os', TextType::class, ['label' => 'Os ',])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
        ]);
    }
}
