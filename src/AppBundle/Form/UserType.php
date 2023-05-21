<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id", TextType::class, array(
                'required' => true,
            ))
            ->add("username", TextType::class, array(
                'required' => true,
            ))
            ->add("nombres", TextType::class, array(
                'required' => true,
            ))
            ->add("apellidos", TextType::class, array(
                'required' => true,
            ))
            ->add("email", TextType::class, array(
                'required' => true,
            ))
            ->add("emailp", TextType::class, array(
                'required' => true,
            ))
            ->add("password", TextType::class, array(
                'required' => true,
            ))
            ->add("isActive", CheckboxType::class, array(
                'required' => true,
            ))
            ->add('user_roles');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'appbundle_usertype';
    }
}
