<?php

namespace App\Form;

use App\Entity\evalDofe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalificarDofeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('calificacion', RangeType::class, array(
            'attr' => [
                'required' => true,
                'label' => 'Calificación',
                'min' => 0,
                'max' => 50,
                'onchange' => 'updateTextInput(this.value)'
            ],
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => evalDofe::class
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_medbundle_cafilicardofe';
    }

}
