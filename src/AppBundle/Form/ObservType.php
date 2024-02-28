<?php

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObservType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder

            ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '120')))
       ;
    }
    public function getName()
    {
        return 'observaciones';
    }
}
