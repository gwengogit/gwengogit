<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        	'required' => true,
        	'label' => 'Nom de la couleur'
        ))
        ->add('code', 'text', array(
            'required' => true,
            'label' => 'Code couleur (hexadecimal)',
            'attr' => array('class' => 'form-control color')
        ))
        ->add('save', 'submit', array(
        	'attr' => array('class' => 'btn btn-primary'),
        	'label' => 'Ajouter'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gwengolo\Bundle\ProductBundle\Entity\Color',
            'attr' => array('class' => 'form-control')
        ));
    }

    public function getName()
    {
        return 'addColor';
    }
}