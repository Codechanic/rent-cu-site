<?php

namespace Vibalco\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('image');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\GalleryBundle\Entity\Image',
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'vibalco_gallerybundle_imagetype';
    }

}
