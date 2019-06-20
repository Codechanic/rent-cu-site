<?php

namespace Vibalco\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactTypeFront extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label'=>false,'attr'=>array('placeholder'=>"front.newsletter.placeh", 'class'=>'form-control')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\CommentBundle\Entity\Contact'
        ));
    }

    public function getName()
    {
        return 'vibalco_newsbundle_contacttype';
    }
}
