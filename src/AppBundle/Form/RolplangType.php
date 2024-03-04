<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolplangType extends AbstractType
{

    /**
     * @var int
     */
    public $semanas;

    public function __construct()
    {
        //$this->semanas = 100 / 5;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->semanas = $options['dias']/5;

        $opciones = array();
        $opciones['' . $this->semanas] = '' . $this->semanas;
        $entero = intdiv($this->semanas, 1);
        $decimal = $this->semanas - $entero;
        $temp = $this->semanas - $decimal;
        for ($i = $temp; $i > 0; $i--) {
            $opciones[$i] = $i;
        }

        $builder
            ->add('horas', TextType::class, array(
                'attr' => array('onkeyup' => 'calculo()')
            ))
            ->add('descripcion', TextareaType::class, array('attr' => array('cols' => '100')))
            ->add('rol', EntityType::class, array(
                'class' => 'AppBundle:Rolacademico',
                'choice_label' => 'id',
            ))
            ->add('semanas', ChoiceType::class, array(
                'choices' => $opciones,
                'required' => true,
                'attr' => array('onchange' => 'calculo()')));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Rolplang',
            'dias' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_rolplang';
    }

}
