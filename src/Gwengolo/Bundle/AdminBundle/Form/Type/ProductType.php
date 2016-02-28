<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder->add('name', 'text', array(
        	'required' => true,
        	'label' => 'Nom du produit'
        ))
        ->add('collection', 'entity', array(
            'required' => true,
            'class' => 'GwengoloProductBundle:Collection',
            'label' => 'Collection',
            'property' => 'name'
        ))
        ->add('type', 'entity', array(
            'required' => true,
            'class' => 'GwengoloProductBundle:ProductType',
            'label' => 'Type de produit',
            'property' => 'name'
        ))
        ->add('specificities', 'entity', array(
            'required' => true,
            'class' => 'GwengoloProductBundle:Specificity',
            'label' => '',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true
        ))
        ->add('color', 'entity', array(
            'required' => true,
            'class' => 'GwengoloProductBundle:Color',
            'label' => 'Couleur du cadre (pour les photos)',
            'property' => 'name'
        ))
        ->add('price', 'number', array(
            'required' => true,
            'label' => 'prix de départ'
        ))
        ->add('description', 'textarea', array(
            'required' => true,
            'label' => 'description',
        ))
        ->add('save', 'submit', array(
        	'attr' => array('class' => 'btn btn-primary'),
        	'label' => 'Soumettre le produit'
        )); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gwengolo\Bundle\ProductBundle\Entity\Product',
            'attr' => array('class' => 'form-control')
        ));
    }

    public function getName()
    {
        return 'addProduct';
    }
}