<?php

namespace App\Form;

use App\Entity\redTutores;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class redTutoresType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('f1', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f2', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f3', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f4', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f5', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f6', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f7', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f8', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f9', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f10', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f11', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f12', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f13', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f14', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f15', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f16', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f17', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f18', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f19', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f20', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f21', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f22', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f23', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f24', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f25', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f26', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f27', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f28', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f29', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
            ->add('f30', ChoiceType::class, array('placeholder' => ' ','label' => ' ',
            'choices'   => array( 'Siempre' => '5', 'Casi Siempre' => '4', 'Aveces' => '3', 'Casi Nunca' => '2', 'Nunca' => '1'), 'required'  => true,))
           ->add('observaciones', TextareaType::class, array('required'  => true, 'attr' => array('cols' => '120', 'maxlength' => '512')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => redTutores::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_redtutores';
    }
}
