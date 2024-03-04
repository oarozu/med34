<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('cedula')
            ->add('nombres')
            ->add('apellidos')
            ->add('vinculacion')
            ->add('sigla')
            ->add('centro')
            ->add('zona')
            ->add('periodo')
            ->add('final')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Archivo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_archivo';
    }
}
