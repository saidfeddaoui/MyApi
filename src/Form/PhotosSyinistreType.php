<?php

namespace App\Form;

use App\Entity\PhotosSinistre;
use Symfony\Component\Form\AbstractType;
use App\Entity\Item;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PhotosSyinistreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('type', EntityType::class, array(
                'class' => Item::class,
                'query_builder' => function($repository) { return $repository->createQueryBuilder('c')->leftJoin('c.itemList', 'y')->where('y.id = 3')->orderBy('c.id', 'ASC'); },
                'choice_label' => 'title',
            ))
            ->add('_img', FileType::class, array(
                'label' => 'Image ',
                'required' => false,
                'mapped' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhotosSinistre::class,
        ]);
    }
}
