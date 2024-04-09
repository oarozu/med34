<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ofertaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $peracas = $options['peracas'];
        $builder
            ->add('cedula', TextType::class, array('required' => true))
            ->add('periodo', ChoiceType::class, array(
                'required' => true,
                'placeholder' => 'Periodo',
                'choices' => $peracas
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Entity\OfertaDatos',
            'peracas' => null
        ));
    }

    public function getName()
    {
        return 'oferta';
    }
}
