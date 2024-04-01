<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use App\Entity\Zona;
use App\Entity\Departamento;


class CentroType extends AbstractType
{
        /**
         * @param FormBuilderInterface $builder
         * @param array $option
         * @var Zona $zona
         */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('tipo', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('CEAD' => 'CEAD', 'CCAV' => 'CCAV','UDR' => 'UDR'),
            'required'  => true,
            ))
            ->add('zona', EntityType::class, array(
                'class' =>  'AppBundle:Zona',
                'choice_label' => 'nombre',
            ))
            ->add('departamento', EntityType::class, array(
                'class' =>  'AppBundle:Departamento',
                'placeholder' => ' ',
                'choice_label' => 'nombre',
            ))
            ->add("cedulaDirector", TextType::class, array(
                "mapped" => true,
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
            'data_class' => 'AppBundle\Entity\Centro'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_centro';
    }
}
