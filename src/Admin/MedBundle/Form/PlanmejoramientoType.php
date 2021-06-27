<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlanmejoramientoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '60')))
            ->add('docente', EntityType::class, array(
                 'class' =>  'AdminUnadBundle:Docente',
                'choice_label' => 'user.nombres',
                 ))
            ->add('calificacion', ChoiceType::class, array(
            'choices'   => array('1' => 'NO', '5' => 'SI'),
            'required'  => true,))   
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\Planmejoramiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_planmejoramiento';
    }
}
