<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'front.contactform.form.name', 'attr' => array('class' => 'form-control', 'placeholder' => 'front.contactform.form.name')))
            ->add('email', null, array('label' => 'front.contactform.form.email', 'attr' => array('class' => 'form-control', 'placeholder' => 'front.contactform.form.email')))
            ->add('phone', null, array('label' => 'front.contactform.form.phone', 'attr' => array('class' => 'form-control', 'placeholder' => 'front.contactform.form.phone')))
            ->add('fromdate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('class' => 'form-control datepicker', 'placeholder' => 'front.contactform.form.fromdate'),
                'label' => 'front.contactform.form.fromdate'
            ))            
            ->add('todate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('class' => 'form-control datepicker', 'placeholder' => 'front.contactform.form.todate'),
                'label' => 'front.contactform.form.todate'
            ))
            ->add('message', null, array('label' => 'front.contactform.form.message', 'attr' => array('class' => 'form-control', 'placeholder' => 'front.contactform.form.message')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\ContactForm'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_mainbundle_contactform';
    }
}
