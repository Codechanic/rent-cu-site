<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class PromoCoverType extends PromoType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('isnew', 'checkbox', array('label' => 'Nuevo'))
        ;
    }
}
