<?php
// src/Acme/DemoBundle/Form/Type/GenderType.php
namespace Vibalco\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditorType extends AbstractType
{
    
  public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults(array(         
            'attr' => array('class'=>'editor'), 
           'label'=>false,          
        ));
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'editor';
    }
}