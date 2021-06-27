<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '110')))
            ->add('autoevaluacion', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '1', 'Nunca' => '1', 'No Aplica' => '0'),
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
