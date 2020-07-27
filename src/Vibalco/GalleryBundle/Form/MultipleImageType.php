<?php

namespace Vibalco\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MultipleImageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('images', 'file', array(
            'attr' => array(
                'multiple' => true,
            )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null,
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'vibalco_gallerybundle_multiple_imagetype';
    }

}
