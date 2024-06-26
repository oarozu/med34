<?php

namespace App\Form;

use App\Entity\Curso;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CursoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('nivel')
            ->add('tipologia')
            ->add('creditos')
            ->add('escuela')
           ->add('Programa', EntityType::class, array(
            'class' =>  'App:Programa',
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
            'data_class' => Curso::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_appbundle_curso';
    }
}
