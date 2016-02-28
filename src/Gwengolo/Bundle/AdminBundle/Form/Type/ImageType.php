<?php

namespace Gwengolo\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'file', array(
        	'required' => true,
        	'label' => "Selectionnez l'image",
            'mapped' => false
        ))
        ->add('save', 'submit', array(
        	'attr' => array('class' => 'btn btn-primary'),
        	'label' => 'Uploader'
        ));
    }

    public function getName()
    {
        return 'addImage';
    }
}