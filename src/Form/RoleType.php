<?php

namespace App\Form;

use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', TextType::class, ['label' => 'Code ', 'required' => true])
            ->add('name', TextType::class, ['label' => 'Name ', 'required' => true])
            ->add('parents', EntityType::class, [
                'class' => Role::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $queryBuilder = $er->createQueryBuilder('r');
                    if ($options['data'] && $id = $options['data']->getId()) {
                        $queryBuilder->where('r.id <> :id')->setParameter('id', $id);
                    }
                    return $queryBuilder;
                },
                'required' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => 'Parents'
            ])
            ->add('children', EntityType::class, [
                'class' => Role::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $queryBuilder = $er->createQueryBuilder('r');
                    if ($options['data'] && $id = $options['data']->getId()) {
                        $queryBuilder->where('r.id <> :id')->setParameter('id', $id);
                    }
                    return $queryBuilder;
                },
                'required' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => 'Fils',
            ])
        ;
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }

}
