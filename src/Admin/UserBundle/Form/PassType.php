<?php

namespace Admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", "text", array(
            'required'  => true,
            ))
            ->add("email", "text", array(
            'required'  => true,
            ))
            ->add('vinculacion', ChoiceType::class, array(
            'placeholder' => 'Vinculación:',
            'choices'   => array('Ocasional' => 'Ocasional', 'Hora Catedra' => 'Hora Catedra','De Carrera' => 'De Carrera', 'DOFE' => 'DOFE'),
            'required'  => true,
            ))
            ->add('unidad', ChoiceType::class, array(
            'placeholder' => 'Escuela/Unidad:',
            'choices'   => array('10000'=>'ECACEN', '15000'=>'ECISALUD','20000' => 'ECBTI', '30000' => 'ECAPMA', '40000' => 'ECSAH', '50000' => 'ECEDU', '25000' => 'ECJP', '60000' => 'VIMEP', '65000' => 'VIACI', '40002' => 'INVIL', '70000' => 'VIDER','80000' => 'VISAE', '90000' => 'VIREL' ),
            'required'  => true,
            ));
    }

    public function getName()
    {
        return 'admin_userbundle_passtype';
    }
}
