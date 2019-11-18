<?php

namespace Vibalco\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client','text', array(
                'attr' => array(
                    'placeholder' => 'front.contact.name',
                    'pattern'     => '.{2,}', //minlength
                    'class'=>"form-control"
                ),
                
            ))
            ->add('phone',null, array(
                'attr' => array(
                    'placeholder' => 'front.contact.phonenumber',
                    'class'=>"form-control",
                    'type'=>'phone'
                )
            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'front.contact.youremail',
                    'class'=>"form-control",
                    'msg-required'=>"Please enter your email address.",
                    'data-msg-email'=>"Please enter a valid email address.",
                     'maxlength'=>"100"
                )
            ))
            ->add('comment', 'textarea', array(
                'attr' => array(                     
                    'rows' => 6,
                    'placeholder' => '...',
                    'data-msg-required'=>"Please enter your message.",
                     'class'=>"form-control" 
                )))
             
        ;
    }
    

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\CommentBundle\Entity\CommentVisitors'
        ));
    }

    public function getName()
    {
        return 'vibalco_commenthomebundle_commenttype';
    }
}
