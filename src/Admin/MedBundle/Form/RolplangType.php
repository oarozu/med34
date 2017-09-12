<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RolplangType extends AbstractType {

    /**
     * @var int
     */
    public $semanas;

    public function __construct($dias = null) {
        $this->semanas = $dias/5;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $opciones = array();
        
        $opciones[''.$this->semanas] = ''.$this->semanas;
        $entero = intdiv($this->semanas , 1);
        $decimal = $this->semanas - $entero;
        $temp = $this->semanas-$decimal;
        for ($i = $temp; $i > 0; $i--) {  
         $opciones[$i] = $i;
        }

        $builder
                ->add('horas', 'text', array('attr' => array('onkeyup' => 'calculo()')))
                ->add('descripcion', 'textarea', array('attr' => array('cols' => '100')))
                ->add('rol', 'entity', array(
                    'class' => 'AdminMedBundle:Rolacademico',
                    'property' => 'id',
                ))
                ->add('semanas', 'choice', array(
               //     'empty_value' => 'Semanas',
                    'choices' => $opciones,
                    'required' => true,
                    'attr' => array('onchange' => 'calculo()')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\Rolplang'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_medbundle_rolplang';
    }

}
