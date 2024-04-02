<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccionespmType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
        $builder
            ->add('plan',EntityType::class, array(
            'class' =>  'App:Planmejoramiento',
            'choice_label' => 'id',
                 ))
            ->add('oportunidad', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '60')))

            ->add('accion', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '60')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Accionespm'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_accionespm';
    }
}
