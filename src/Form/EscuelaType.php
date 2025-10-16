<?php

namespace App\Form;

use App\Entity\Escuela;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EscuelaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('nombre')
            ->add('sigla')
            ->add("decano", TextType::class, array(
             "mapped" => false,
            'required'  => true,
                ))
            ->add("secretaria", TextType::class, array(
             "mapped" => false,
            'required'  => true,
                ))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Escuela::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_escuela';
    }
}
