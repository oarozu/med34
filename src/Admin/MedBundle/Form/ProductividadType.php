<?php

namespace Admin\MedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Admin\MedBundle\Entity\Proyectoi;
use Admin\MedBundle\Entity\ProyectoiRepository;


class ProductividadType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public $user;
    
    public function __construct($user = null) {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('tipo', TextareaType::class, array('required' => true, 'attr' => array('cols' => '80', 'rows' => '1')))
        ->add('articulacion', ChoiceType::class, array(
        'placeholder' => 'Seleccione una...',
        'choices' => array(
        'Formación Integral (Docencia)' => 'Docencia',
        'Desarrollo Regional (Proyeción Social)' => 'Proyección Social',
        'Internacionalización' => 'Internacionalización',
        'Inclusión Participación y cooperación' => 'Inclusión',
        'Innovación' => 'Invonvación',
        ),
        // *this line is important*
        'choices_as_values' => true,
        'expanded' => false,
        'multiple' => false,
        'required' => true
        ))
        ->add('descripcion', EntityType::class, array('required' => true, 'attr' => array('cols' => '80', 'rows' => '5')))
                
                ->add('proyecto', EntityType::class, array(
                'placeholder' => 'Seleccione un proyecto',
                'class' => 'AdminMedBundle:Proyectoi',
                'choice_label' => 'nombre',
                'query_builder' => function(ProyectoiRepository $repo) {
                return $repo->porUsuario($this->user);
                }
            ))
;
       
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\MedBundle\Entity\Productividad'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_medbundle_productividad';
    }

}
