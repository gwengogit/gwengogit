<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        	'required' => true,
        	'label' => 'Nom de la collection'
        ))
        ->add('save', 'submit', array(
        	'attr' => array('class' => 'btn btn-primary'),
        	'label' => 'Ajouter'
        ));
    }

    public function getName()
    {
        return 'addCollection';
    }
}