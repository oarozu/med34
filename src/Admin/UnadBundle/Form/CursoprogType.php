<?php

namespace Admin\UnadBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Admin\UnadBundle\Entity\ProgramaRepository;
use Admin\UnadBundle\Entity\EscuelaRepository;


class CursoprogType extends AbstractType
{
    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $escuelaid = $options['escuela'];
        $siglas = $options['siglas'];
        $builder
            ->add('nombre', TextType::class, array(
                'attr' => array('size' => '100')
            ))
            ->add('nivel', ChoiceType::class, array(
                'placeholder' => ' ',
                'choices' => array('Básico' => 'Básico', 'Electivo' => 'Electivo', 'Especialización' => 'Especialización', 'Grado' => 'Grado', 'Posgrado' => 'Posgrado'),
                'required' => true,
            ))
            ->add('escuela', ChoiceType::class, array(
                'required' => true,
                'choices' => $siglas
            ))
            ->add('programa', EntityType::class, array(
                'required' => true,
                'placeholder' => 'Programa',
                'class' => 'AdminUnadBundle:Programa',
                'choice_label' => 'nombre',
                'query_builder' => function (ProgramaRepository $repo) use ($escuelaid) {
                    return $repo->findByEscuela($escuelaid);
                }
                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\UnadBundle\Entity\Curso',
            'escuela' => null,
            'siglas' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_unadbundle_curso';
    }
}
