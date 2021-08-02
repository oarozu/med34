<?php

namespace Admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CedulaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $builder
                    ->add('id');
    }

    public function getName()
    {
        return 'cedula';
    }
}