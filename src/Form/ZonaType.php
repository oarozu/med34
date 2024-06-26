<?php

namespace App\Form;

use App\Entity\Zona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ZonaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add("director", TextType::class, array(
                "mapped" => false,
                'required'  => true,
            ))
            ->add("director_nom", TextType::class, array(
                "mapped" => false,
                'required'  => true,
                'disabled' => true
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Zona::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_zona';
    }
}
