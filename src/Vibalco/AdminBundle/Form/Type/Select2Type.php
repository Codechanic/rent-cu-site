<?php
// src/Acme/DemoBundle/Form/Type/GenderType.php
namespace Vibalco\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Select2Type extends AbstractType
{
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults(array(
            'attr' => array('class'=>'select2 form-control'), 
           
        ));
    }
  

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'select2';
    }
}