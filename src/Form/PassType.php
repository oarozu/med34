<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", TextType::class, array(
            'required'  => true,
            ))
            ->add("email", TextType::class, array(
            'required'  => true,
            ))
            ->add('unidad', ChoiceType::class, array(
            'placeholder' => 'Escuela/Unidad:',
            'choices'   => array('ECACEN'=>'10000', 'ECISALUD'=>'15000','ECBTI' => '20000', 'ECAPMA' => '30000', 'ECSAH' => '40000', 'ECEDU' => '50000', 'ECJP' => '25000', 'VIMEP' => '60000', 'VIACI' => '65000', 'INVIL' => '40002', 'VIDER' => '70000','VISAE' => '80000', 'VIREL' => '90000'),
            'required'  => true,
            ));
    }

    public function getName()
    {
        return 'appbundle_passtype';
    }
}
