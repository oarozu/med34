<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstrumentoType extends AbstractType
{
        
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required'  => true,))
            ->add('tipo')
            ->add('descripcion')
            ->add('estado', ChoiceType::class, array(
            'choices'   => array( '1' => 'Activo', '0' => 'Inactivo'),
            'required'  => true,))       
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\Instrumento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_instrumento';
    }
}
