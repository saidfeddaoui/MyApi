<?php

namespace App\Form;

use App\Entity\TiersAttachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TiersAttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nature', 'entity', array(
                'label'  => false,
                'empty_value' => 'Sélectionnez',
                'class' => 'App\Entity\NatureDoc',
                'query_builder' => function($repository) { return $repository->createQueryBuilder('t')->orderBy('t.id', 'ASC'); },
                'property' => 'libelle',
                'expanded'  =>false,
                'multiple'  =>false,
                'mapped'    => true,
                'required'  =>false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TiersAttachment::class,
        ]);
    }
}
