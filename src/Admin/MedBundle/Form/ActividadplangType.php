<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadplangType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('observaciones', 'textarea', array('required'  => true, 'attr' => array('cols' => '110')))
            ->add('autoevaluacion', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'),
            'required'  => true,))
           ->add('file')     
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\Actividadplang'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_actividadplang';
    }
}
