<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DocenteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modalidad', ChoiceType::class, array(
            'choices'   => array('TC' => 'Tiempo Completo', 'MT' => 'Medio Tiempo'),
            'required'  => true,
            ))
            ->add('vinculacion', ChoiceType::class, array(
            'choices'   => array('HC' => 'Hora Catedra', 'DO' => 'Ocasional','DC' => 'Carrera' ),
            'required'  => true,
            ))
            ->add('cargo')
            ->add('resolucion')
            ->add('perfil')
            ->add('escuela', EntityType::class, array(
                 'class' =>  'App:Escuela',
                'choice_label' => 'sigla',
                 ))
            ->add('centro', EntityType::class   , array(
                 'class' =>  'App:Centro',
                'choice_label' => 'nombre',
                 ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Entity\Docente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_docente';
    }
}
