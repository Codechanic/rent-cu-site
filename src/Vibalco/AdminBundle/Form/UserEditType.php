<?php

namespace Vibalco\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('file','file',array('image_path' => 'webPath'))   
                ->add('username')
                ->add('name')
                ->add('roles')
                ->add('email');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\AdminBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'vibalco_adminbundle_useredittype';
    }

}
