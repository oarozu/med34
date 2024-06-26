<?php

namespace App\Form;

use App\Entity\Programa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Entity\Escuela;

class ProgramaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,  array(
            'attr' => array('size' => '100')
            ))
            ->add('nivel', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('Diplomado' => 'Diplomado', 'Especialización' => 'Especialización','Licenciatura' => 'Licenciatura', 'Maestria' => 'Maestria','Profesional' => 'Profesional','Tecnología' => 'Tecnología','Unidad'=>'Unidad'),
            'required'  => true,
            ))
            ->add('escuela', EntityType::class, array(
                'placeholder' => ' ',
                'class' =>  'App:Escuela',
                'choice_label' => 'nombre',
            ))
            ->add("lider", TextType::class, array(
             "mapped" => false,
            'required'  => true,
            'attr' => array('readonly' => 'readonly')
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Programa::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_programa';
    }
}
