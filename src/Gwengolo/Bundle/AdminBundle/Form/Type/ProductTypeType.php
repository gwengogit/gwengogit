<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        	'required' => true,
        	'label' => 'Nom du type de produit (ex: Turbulette, Couverture..)'
        ))
        ->add('save', 'submit', array(
        	'attr' => array('class' => 'btn btn-primary'),
        	'label' => 'Ajouter'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gwengolo\Bundle\ProductBundle\Entity\ProductType',
            'attr' => array('class' => 'form-control')
        ));
    }

    public function getName()
    {
        return 'addProductType';
    }
}