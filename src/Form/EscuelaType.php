<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EscuelaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('nombre')
            ->add('sigla')
            ->add("decano", "text", array(
             "mapped" => false,
            'required'  => true,
                ))
            ->add("secretaria", "text", array(
             "mapped" => false,
            'required'  => true,
                ))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Escuela'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_escuela';
    }
}
