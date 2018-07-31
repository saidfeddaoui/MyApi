<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\InsuranceType;
use App\Entity\Mutuelle;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => 'Groupe'
            ])
            ->add('insuranceTypes', EntityType::class, [
                'class' => InsuranceType::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Mutuelles'
            ])
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'label' => 'Rôles'
            ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
