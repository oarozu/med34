<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalificarDofeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('calificacion', ChoiceType::class, array(
            'choices' => array(
                'Superior' => 30,
                'Medio' => 20,
                'Bajo' => 10
            ),
            // *this line is important*
            'choices_as_values' => true,
             'expanded' => true,
             'multiple' => false,
             'required'  => true
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\evalDofe'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_medbundle_cafilicardofe';
    }

}
