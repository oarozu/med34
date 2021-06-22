<?php

namespace Admin\MedBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ofertaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
            ->add('cedula', TextType::class, array('required'  => true))
                        
          ->add('periodo', EntityType::class, array(
          'class' =>  'AdminMedBundle:Periodoa',
          'choice_label' => 'id',
          ))  
        ;  
    }

    public function getName()
    {
        return 'oferta';
    }
}