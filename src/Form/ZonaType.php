<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ZonaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add("director", "text", array(
                "mapped" => false,
                'required'  => true,
            ))
            ->add("director_nom", "text", array(
                "mapped" => false,
                'required'  => true,
                'disabled' => true
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Zona'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_zona';
    }
}
