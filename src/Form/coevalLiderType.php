<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class coevalLiderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('f1', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0',), 'required'  => true,))
            ->add('f2', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0',), 'required'  => true,))
            ->add('f3', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f4', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f5', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f6', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f7', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f8', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f9', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f10', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f11', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f12', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f13', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('f14', ChoiceType::class, array('placeholder' => '-', 'label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1', 'No Aplica' => '0'), 'required'  => true,))
            ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '90')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\coevalLider'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_coevallider';
    }
}
