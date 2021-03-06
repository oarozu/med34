<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class coevalTutorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('f1', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica',), 'required'  => true,))
            ->add('f2', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica',), 'required'  => true,))
            ->add('f3', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f4', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f5', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f6', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f7', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f8', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f9', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f10', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f11', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f12', 'choice', array('empty_value' => '-', 'label' => ' ',    
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('observaciones', 'textarea', array('required'  => true, 'attr' => array('cols' => '120', 'maxlength' => '512')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\coevalTutor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_coevaltutor';
    }
}
