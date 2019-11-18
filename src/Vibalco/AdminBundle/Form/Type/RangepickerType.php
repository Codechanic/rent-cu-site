<?php
// src/Acme/DemoBundle/Form/Type/GenderType.php
namespace Vibalco\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RangepickerType extends AbstractType
{
    
  public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults(array(
            'attr' => array('class'=>'rangepicker'),         
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'rangepicker';
    }
}