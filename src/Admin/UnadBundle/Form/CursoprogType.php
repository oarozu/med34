<?php
namespace Admin\UnadBundle\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CursoprogType extends AbstractType
{
protected $escuelaid;
public function __construct ()
{
    $this->escuelaid = 10000;
}       
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $escuelaid = $this->escuelaid;
        $builder
            ->add('nombre', TextType::class,  array(
            'attr' => array('size' => '100')
            ))  
            ->add('nivel', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('Básico' => 'Básico', 'ELECTIVO' => 'ELECTIVO','Especialización' => 'Especialización', 'Grado' => 'Grado','Posgrado' => 'Posgrado'),
            'required'  => true,
            ))
            ->add('tipologia', ChoiceType::class, array(
            'placeholder' => ' ',
            'choices'   => array('Metodológico' => 'Metodológico', 'Práctico' => 'Práctico','Recontextual' => 'Recontextual', 'Teórico' => 'Teórico','Teórico/Práctico' => 'Teórico/Práctico'),
            'required'  => true,
            ))     
            ->add('creditos')
            ->add("escuela", TextType::class, array(
            'attr' => array('readonly' => 'readonly')))    
            ->add("director", TextType::class, array(
             "mapped" => false,
            'required'  => true,
            'attr' => array('readonly' => 'readonly')    
                ))    
            ->add('programa', EntityType::class, array(
            'required' => true,
            'placeholder' => ' ',
            'class' => 'AdminUnadBundle:Programa',
            'choice_label' => 'nombre',
            'query_builder' => function(\Admin\UnadBundle\Entity\ProgramaRepository $er) use ($escuelaid) {
            return $er->createQueryBuilder('c')
            ->where('c.escuela = :escuelaid')
            ->setParameter('escuelaid', $escuelaid);
            }))
                
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\UnadBundle\Entity\Curso'
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
