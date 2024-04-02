<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ProyectoiRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductividadType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public $user;

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
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
            ->add('descripcion', TextareaType::class, array('required' => true, 'attr' => array('cols' => '80', 'rows' => '5')))
            ->add('proyecto', EntityType::class, array(
                'placeholder' => 'Seleccione un proyecto',
                'class' => 'App:Proyectoi',
                'choice_label' => 'nombre',
                'query_builder' => function (ProyectoiRepository $repo) {
                    return $repo->porUsuario($this->user);
                }
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Productividad',
            'user' => null
        ]);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_medbundle_productividad';
    }

}
