<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpecificityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        	'required' => true,
        	'label' => 'Nom de la spécificité',
            'attr' => array('class' => 'form-control')
        ))
        ->add('description', 'textarea', array(
            'required' => true,
            'label' => 'Description',
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
            'data_class' => 'Gwengolo\Bundle\ProductBundle\Entity\Specificity',
            'attr' => array('class' => 'form-control')
        ));
    }

    public function getName()
    {
        return 'addSpecificity';
    }
}