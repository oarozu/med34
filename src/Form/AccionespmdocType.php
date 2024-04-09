<?php

namespace App\Form;

use App\Entity\Accionespm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccionespmdocType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
                    {
        $builder
        ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '60')))
        ->add('file', 'file', array('label' => ' ','required'  => true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Accionespm::class
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
