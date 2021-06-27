<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class coevalDirectorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('f1', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica',), 'required'  => true,))
            ->add('f2', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica',), 'required'  => true,))
            ->add('f3', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f4', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f5', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f6', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f7', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f8', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f9', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f10', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f11', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f12', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f13', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f14', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f15', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f16', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('f17', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( '5' => 'Siempre', '4' => 'Casi Siempre', '3' => 'Aveces', '2' => 'Casi Nunca', '1' => 'Nunca', '0' => 'No Aplica'), 'required'  => true,))
            ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '120', 'maxlength' => '512')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\coevalDirector'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_coevaldirector';
    }
}
