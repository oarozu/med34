<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadplangAddType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         ->add("actividad", "text", array(
         "mapped" => false,
         'required'  => true,
         /* 'attr' => array('readonly' => 'readonly')*/
                 )) 
         ->add('horas')
         ->add('descripcion', 'textarea', array('attr' => array('cols' => '60')))
        
         ->add('actividad', EntityType::class, array(
          'class' =>  'AdminMedBundle:Actividadrol',
          'choice_label' => 'id',
          ))         
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
