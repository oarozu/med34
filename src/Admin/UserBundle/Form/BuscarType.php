<?php

namespace Admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class BuscarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $builder
                ->add(TextType::class)
                ->add('parametro', ChoiceType::class, array(
                'placeholder' => 'Buscar por:',
                'choices'   => array('Cédula' => 'ced', 'Nombres' => 'nom','Apellidos' => 'apell'),
                'required'  => true,
                ));
    }

    public function getName()
    {
        return 'buscar';
    }
}